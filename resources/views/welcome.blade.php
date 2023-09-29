<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This store is one of the biggest book suppliers around the world!">
    <title>Laravel</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ URL::asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ URL::asset('favicon.png') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</head>

<body class="antialiased">
    <div class="container mt-5">
        <form method="GET" action="{{ route('home') }}" class="d-flex">
            <div class="input-group w-50 me-4">
                <input type="search" name="search" value="{{ $searchQuery }}" class="form-control w-75"
                    placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                <select class="form-select w-25" aria-label="Category selector" name="search_category">
                    <option {{ 'all' == $searchCategory ? 'selected' : '' }} value="all">All</option>
                    <option {{ 'book_name' == $searchCategory ? 'selected' : '' }} value="book_name">Book name</option>
                    <option {{ 'author_name' == $searchCategory ? 'selected' : '' }} value="author_name">Author name
                    </option>
                </select>
            </div>
            <select class="form-select me-4 w-25" aria-label="Date selector" name="date_selected">
                @foreach ($dates as $date)
                    <option {{ $date == $filterDate ? 'selected' : '' }} value="{{ $date }}">{{ $date }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <h2 class="mt-5">List of books</h2>
        @if ($books->isEmpty())
            <h1>No books found</h1>
        @else
            <h1>Found {{ $books->count() }} books</h1>
            <div class="book_section">
                @foreach ($books as $book)
                    <div class="book card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">
                                {{ $book->author()->pluck('name')->implode(', ') }}
                            </h6>
                            <div class="order-info">
                                <span>Ordered:</span>
                                <span class="card-text"><span>{{ $book->salesInTime }}</span> times in this
                                    period</span>
                                <span class="card-text"><span>{{ $book->sales->sum('quantity') }}</span> times in
                                    total</span>
                            </div>
                            <form method="POST" action="{{ route('buyBook') }}">
                                <input type="hidden" name="bookId" value="{{ $book->id }}">
                                <input type="hidden" name="filterDate" value="{{ $filterDate }}">
                                <input type="hidden" name="searchQuery" value="{{ $searchQuery }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary">Buy</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>

</html>
