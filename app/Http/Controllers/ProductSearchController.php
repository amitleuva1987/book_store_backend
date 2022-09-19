<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Response;
use Elasticsearch\ClientBuilder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductSearchController extends Controller
{
    protected $client;
    public function  __construct()
    {
        $this->client = ClientBuilder::create()
        ->setHosts(config('scout.elasticsearch.hosts'))
        ->build();
    }

    public function search(Request $request){
    try{ 
        if($request->filled('filter')){   
            $products = Product::Search()->where($request->filter,$request->search)->get();
        } else {
            $products = Product::Search($request->search)->get();
        }
        return new ProductResource($products);
    } catch(\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ],Response::HTTP_INTERNAL_SERVER_ERROR);
    }    
    }

    public function allBooks(){
        try{
            return new ProductResource(Product::all());
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }

    public function elasticsearch(Request $request)
    {
        $params = [
            'index' =>'products',
        //    'filter_path' => ['hits.hits._source'],
            'size' => '100',
            'body' => [
                "sort" => [
                    [ "published" => ["order" => "desc"]]
                ],
                'query' => [
                    "range" => [
                        'published' => [
                            'gte' => $request->startDate,
                            'lte' => $request->endDate,
                        ]
                    ]
                ],
            ]
        ];
        
        $products = $this->client->search($params);
        
        if($products['hits']['total']['value'] > 0){
            foreach($products['hits']['hits'] as $product){
                $data[] = $product['_source'];
            }
         } else {
            $data = [];
        }

        $options = ['path' => 'http://localhost/api/elasticsearch'];

        $result_collection = $this->paginate($data,10,null,$options);
        return response()->json($result_collection);
    }

    public function genreFilter()
    {
        $params = [
            'index' =>'products',
            'size' => '0',
            'body' => [
                "aggs" => [
                    "group_by_genre" => [
                        "terms" => [
                            "field" => 'genre.keyword'
                        ]
                    ]
                ],
            ]
        ];
        
        $results = $this->client->search($params);
        if($results['aggregations']['group_by_genre']['sum_other_doc_count'] > 0){
            $products['data'] = $results['aggregations']['group_by_genre']['buckets'];
        } else {
            $products['data'] = [];
        }
        

        return response()->json($products);
    }

    public function genreFilterData(Request $request)
    {
        $params = [
            'index' =>'products',
            'size' => '100',
            'body' => [
                "query" => [
                        "terms" => [
                            "genre.keyword" => $request->selectedGenre,
                            "boost" => 1.0
                        ]
                ],
            ]
        ];

        $products = $this->client->search($params);
        
        if($products['hits']['total']['value'] > 0){
            foreach($products['hits']['hits'] as $product){
                $data[] = $product['_source'];
            }
         } else {
            $data = [];
        }

        $options = ['path' => 'http://localhost/api/get_genre_filtered_data'];
        $collection_object = collect($data);
        $result_collection = $this->paginate($collection_object,10,null,$options);
        return response()->json($result_collection);
    }


    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
