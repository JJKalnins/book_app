<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Sale;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use DatePeriod;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->search;
        if (empty($searchQuery)) {
            $books = Book::all();
        } else {
            $searchParams = ['name', 'like', '%' . $searchQuery . '%'];

            $books = match ($request->search_category) {
                "book_name" => Book::where(...$searchParams)->get(),
                "author_name" => Book::whereHas('author', function ($q) use ($searchParams) {
                    $q->where(...$searchParams);
                })->get(),
                default => Book::where(...$searchParams)
                    ->orWhereHas('author', function ($q) use ($searchParams) {
                        $q->where(...$searchParams);
                    })->get()
            };
        }

        if (!empty($request->date_selected)) {
            $filterDate = $request->date_selected;
        } else {
            $filterDate = "now";
        }

        $dates = $this->getDatesForSelect();
        $minMaxDate = $this->getMinMaxDate($filterDate);
        $books = $books->sortByDesc(function ($book) use ($minMaxDate) {
            $book->salesInTime = $book->sales->whereBetween("created_at", [$minMaxDate['min'], $minMaxDate['max']])->sum("quantity");
            return $book->salesInTime;
        });

        return view('welcome', [
            'books' => $books,
            'dates' => $dates,
            'filterDate' => $filterDate ?? $dates[0],
            'searchQuery' => $searchQuery,
            'searchCategory' => $request->search_category,
        ]);
    }

    private function getDatesForSelect(): array
    {
        [$start, $end] = $this->calcFirstLastDay(Sale::min("created_at"), Sale::max("created_at"));

        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $dates = [];
        foreach ($period as $dt) {
            $dates[] = $dt->format("Y-m");
        }

        rsort($dates);
        return $dates;
    }

    private function getMinMaxDate(String $filterDate): array
    {
        [$start, $end] = $this->calcFirstLastDay($filterDate, $filterDate);

        return [
            "min" => $start->format("Y-m-d"),
            "max" => $end->format("Y-m-d")
        ];
    }

    private function calcFirstLastDay(String $first, String $last): array
    {
        $start = new DateTime($first);
        $end = new DateTime($last);
        $start->modify('first day of this month');
        $end->modify('last day of this month');

        return [$start, $end];
    }

    public function buyBook(Request $request)
    {
        $newSale = new Sale;
        $newSale->book_id = $request->bookId;
        $newSale->quantity = 1;
        $newSale->save();
        return redirect('/');
    }

    public function topTen(): array
    {
        $books = Book::all();
        $minMaxDate = $this->getMinMaxDate("now");

        $books = $books->sortByDesc(function ($book) use ($minMaxDate) {
            $book->salesInTime = $book->sales->whereBetween("created_at", [$minMaxDate['min'], $minMaxDate['max']])->sum("quantity");
            return $book->salesInTime;
        });
        $books = $books->take(10)->makeHidden(["id", "created_at", "updated_at", "sales"])->toArray();

        return array_values($books);
    }
}
