<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = file_get_contents('https://fakerapi.it/api/v1/books?_quantity=100');
        $products = json_decode($response);

        foreach($products->data as $product){
            $data = [
                'title' => $product->title,
                'author' => $product->author,
                'genre' => $product->genre,
                'description' => $product->description,
                'isbn' => $product->isbn,
                'image' => $product->image,
                'published' => $product->published,
                'publisher' => $product->publisher,
            ];
            Product::create($data);
        }
    }
}
