<?php
namespace App\Imports;

use App\User;
use DB;
use Exception;
use Lang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BulkUserImport implements ToModel, WithHeadingRow
{
    private $errors = [];
    public function model(array $row)
    {
        $request = request()->all();
        $dateValue = $row['join_date'];
        $dateObject = \DateTime::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue)->format('Y-m-d'));
        $dateString = $dateObject->format('Y-m-d');

        $email = $row['email'];
        $nik = $row['nik'];

        if (User::withoutGlobalScope('active')->whereEmail($email)->exists()) {
            $this->addError('email', Lang::get('The email has already been taken.'));
            return null;
        }

        if (User::withoutGlobalScope('active')->whereNik($nik)->exists()) {
            $this->addError('nik', Lang::get('The NIK has already been taken.'));
            return null;
        }

        DB::beginTransaction();
        try {
            $user = new User;
            $user->type = $request['tipe'];
            $user->position_id = $request['jabatan'];
            $user->project_id = $request['projek'];
            $user->name = $row['name'];
            $user->nik = $row['nik'];
            $user->email = $row['email'];
            $user->password = bcrypt('pass1234');
            $user->join_date = $dateString;
            $user->save();

            $user->syncRoles([$request['wewenang']]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $user->sendEmailVerificationNotification();
    }

    public function addError($attribute, $message)
    {
        $this->errors[] = [
            'attribute' => $attribute,
            'message' => $message,
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
