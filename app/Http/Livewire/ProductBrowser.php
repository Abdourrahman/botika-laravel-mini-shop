<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

use function PHPSTORM_META\map;

class ProductBrowser extends Component
{
    /**
     * category
     *
     * @var mixed
     */
    public $category;

    /**
     * queryFilters
     *
     * @var array
     */
    public $queryFilters = [];

    public $priceRange = [
        'max' => null
    ];

    public function mount()
    {
        $this->queryFilters = $this->category->products->pluck('variations')
            ->flatten()
            ->groupBy('type')
            ->keys()
            ->mapWithKeys(fn ($key) => [$key => []])
            ->toArray();
    }
    public function render()
    {


        $search = Product::search("", function ($meillisearch, string $query, array $options) {
            $options['filter'] = null;

            $filters = collect($this->queryFilters)->filter(fn ($filter) => !empty($filter))
                ->recursive()
                ->map(function ($value, $key) {
                    return $value->map(fn ($value) => $key . ' = "' . $value . '"');
                })
                ->flatten()
                ->join(' AND ');

            // $options['filter'] = 'category_ids = ' . $this->category->id;

            $options['facets'] = ['size', 'color']; // refactor

            if ($filters) {
                $options['filter'] = $filters;
            }

            if ($this->priceRange['max']) {
                $options['filter'] .= (isset($options['filter']) ? ' AND ' : '') . 'price <= ' . $this->priceRange['max'];
            }

            return $meillisearch->search($query, $options);
            
        })->raw();

        $products = $this->category->products->find(collect($search['hits'])->pluck('id'));

        $maxPrice = $this->category->products->max('price');

        $this->priceRange['max'] = $this->priceRange['max'] ?: $maxPrice;

        return view('livewire.product-browser', [
            'products' => $products,
            'filters' => $search['facetDistribution'],
            'maxPrice' => $maxPrice
        ]);
    }
}
