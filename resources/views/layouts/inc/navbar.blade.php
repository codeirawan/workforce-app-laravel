<nav class="navbar col-md-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo/brand.png') }}" width="160px" height="60px" alt="Logo Home" class="pl-md-4 pl-2" />
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">

        <form name="search-course" id="search-course">
            <ul class="navbar-nav">
                <li class="nav-item nav-search d-none d-lg-block">
                    <div class="input-group search-container">
                        <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                            <span class="input-group-text" id="search" type="submit">
                                <i class="icon-search"></i>
                            </span>
                        </div>
                        <input type="text" name="query" class="form-control" id="search-input"
                            placeholder="Search jobs here ..." aria-label="search" aria-describedby="search"
                            autocomplete="off">
                        <div id="search-results">
                        </div>
                    </div>
                </li>
            </ul>
        </form>
        <ul class="navbar-nav">

            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link count-indicator dropdown-toggle" id="show-categories" href="#"
                    data-toggle="dropdown" onclick="showCategories()">
                    Blog
                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="notificationDropdown">
                    <p class="font-weight-normal dropdown-header">List Categories</p>

                    <ul id="category-list" class="pl-0"></ul>
                </div>
            </li>
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link count-indicator dropdown-toggle" id="show-categories" href="#"
                    data-toggle="dropdown" onclick="showCategories()">
                    Categories
                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="notificationDropdown">
                    <p class="font-weight-normal dropdown-header">List Categories</p>

                    <ul id="category-list" class="pl-0"></ul>
                </div>
            </li>
            @auth

                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link" href="{{ route('my-courses') }}">
                        My Applications
                    </a>
                </li>

                {{-- <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link" href="{{ route('my-courses') }}">
                        My Courses
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="{{ route('tenure.new-hire') }}">
                        Learning Journey
                    </a>
                </li> --}}

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="{{ route('wishlist') }}">
                        <i class="icon-heart" style="font-size: 1.3em;"></i>
                    </a>
                </li>

                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                        <img src="{{ asset('images/users/default.jpg') }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">

                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="fa-solid fa-face-laugh-wink"></i>
                            {{ auth()->user()->name }}
                        </a>

                        <a class="dropdown-item" href="{{ route('dashboard') }}" style="border-bottom: 1px solid #CED4DA;">
                           <i class="fa-solid fa-user-astronaut"></i>
                            Administrator
                        </a>

                        <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="kt-nav__link-icon"><i class="fa-solid fa-power-off"></i></span>
                            <span class="kt-nav__link-text">{{ __('Sign Out') }}</span>
                        </a>
                        <form id="logout-form" class="d-none" action="{{ route('logout') }}" method="POST">@csrf
                        </form>
                    </div>
                </li>
            @endauth
            @guest

                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link" href="#about-us">
                        About Us
                    </a>
                </li>

                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link" href="#faq">
                        FAQ
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a href="{{ route('register') }}" type="button" class="btn btn-outline-primary btn-fw">Sign up</a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a href="{{ route('login') }}" type="button" class="btn btn-primary btn-fw">Log in</a>
                </li>
            @endguest
        </ul>
    </div>
</nav>

<style>
    .search-container {
        position: relative;
        width: 200%;
    }

    #search-results {
        position: absolute;
        border-radius: 8px;
        top: 100%;
        z-index: 1;
        width: 100%;
        overflow-y: auto;
        cursor: pointer;
        background-color: #fff;
    }

    #search-results a {
        flex-basis: 75%;
        font-size: 14px;
        color: #000;
    }

    #search-results a:hover {
        color: #0e4fa1;
        text-decoration: underline;
        cursor: pointer;
        border-radius: 8px;
        background-color: #eaeaf1;
    }

    #search-results img {
        flex-basis: 25%;
        max-width: 100%;
        height: auto;
        padding-left: 5px;
        padding-right: 0;
        margin-left: 20px;
    }

    #categocry-list li a {
        color: #000;
    }

    #catexgory-list li a:hover {
        color: #0e4fa1;
        text-decoration: underline;
        cursor: pointer;
    }
</style>

<script src="{{ asset('js/vendor/jquery-3.5.1.min.js') }}"></script>
<script>
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim();
        if (query.length > 0) {
            fetch(`/search?query=${query}`)
                .then(response => response.json())
                .then(results => {
                    // Clear the existing search results
                    searchResults.innerHTML = '';
                    if (results.length > 0) {
                        // Append each result to the search results div
                        results.forEach(result => {
                            const resultElement = document.createElement('div');
                            resultElement.classList.add('row', 'py-1');
                            // Replace with the appropriate field from your search results
                            const resultLink = document.createElement('a');
                            resultLink.textContent = result.name;
                            // Replace with the appropriate URL for your search result
                            if (result && result.slug) {
                                resultLink.href = `/course/${result.slug}`;
                                resultLink.classList.add('col-md-8');
                            }
                            const resultImage = document.createElement('img');
                            if (result && result.image) {
                                resultImage.src = result.image;
                                resultImage.alt = result.name;
                                resultImage.classList.add('rounded', 'col-md-4');
                                resultImage.style.height = '77px';
                                resultImage.style.width = '94px';
                            }
                            resultElement.appendChild(resultImage);
                            resultElement.appendChild(resultLink);
                            searchResults.appendChild(resultElement);
                        });
                    } else {
                        // Display a message indicating that no results were found
                        const noResultsMessage = document.createElement('div');
                        noResultsMessage.textContent = 'No results found.';
                        searchResults.appendChild(noResultsMessage);
                    }
                });
        } else {
            // Clear the search results if the search input is empty
            searchResults.innerHTML = '';
        }
    });
    // Disable enter key
    $('#search-course').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</>
<script>
    function showCategories() {
        const categoryList = document.getElementById('category-list');

        fetch('/category')
            .then(response => response.json())
            .then(categories => {
                // Clear existing list items
                categoryList.innerHTML = '';

                // Loop through categories and create list items
                categories.forEach(category => {
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.textContent = category.name;
                    a.href = `/category/${category.id}`;
                    a.classList.add('dropdown-item', 'preview-item');
                    li.appendChild(a);
                    categoryList.appendChild(li);
                });
            })
            .catch(error => console.error(error));
    }
</script>
