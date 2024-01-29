<?php

class Catalog
{
    protected array $products;

    /**
     * @param array $products The array of products to be cataloged
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * Retrieve the products sorted according to the specified SorterInterface.
     * @param SorterInterface $productSorter The sorter used to sort the products
     * @return array The sorted array of products
     */
    public function getProducts(SorterInterface $productSorter): array
    {
        return $productSorter->sort($this->products);
    }

}


// Sorters

/**
 * Interface SorterInterface
 * Defines a common interface for classes that can sort an array of items.
 */
interface SorterInterface
{
    /**
     * Sort the array of products
     * @param array $products The array of products to be sorted
     * @return array The sorted array of products
     */
    public function sort(array $products): array;
}

/**
 * Class ProductPriceSorter
 * Sorts an array of products by their price.
 */
class ProductPriceSorter implements SorterInterface
{
    protected bool $desc;

    /**
     * @param bool $desc Determines whether the sorting should be done in descending order (default = false)
     */
    public function __construct(bool $desc = false)
    {
        $this->desc = $desc;
    }

    /**
     * Sort the array of products by price
     * @param array $products The array of products to be sorted
     * @return array The sorted array of products
     */
    public function sort(array $products): array
    {
        usort($products, function ($product1, $product2) {
            return !$this->desc ? $product1['price'] <=> $product2['price'] : $product2['price'] <=> $product1['price'];
        });
        return $products;
    }
}

/**
 * Class ProductSalesPerViewSorter
 * Sorts an array of products by their sales per view ratio.
 */
class ProductSalesPerViewSorter implements SorterInterface
{
    protected bool $desc;

    /**
     *
     * @param bool $desc Determines whether the sorting should be done in descending order (default = false)
     */
    public function __construct(bool $desc = false)
    {
        $this->desc = $desc;
    }

    /**
     * Sort the array of products by sales per view ratio
     * @param array $products
     * @return array
     */
    public function sort(array $products): array
    {
        usort($products, function ($product1, $product2) {
            $salesPerViewA = $product1['sales_count'] / $product1['views_count'];
            $salesPerViewB = $product2['sales_count'] / $product2['views_count'];

            return !$this->desc ? $salesPerViewA <=> $salesPerViewB : $salesPerViewB <=> $salesPerViewA;
        });

        return $products;

    }
}


class ProductSalesCountSorter implements SorterInterface
{
    protected bool $desc;

    public function __construct(bool $desc = false)
    {
        $this->desc = $desc;
    }

    public function sort(array $products): array
    {
        usort($products, function ($product1, $product2) {
            return !$this->desc ? $product1['sales_count'] <=> $product2['sales_count'] : $product2['sales_count'] <=> $product1['sales_count'];
        });

        return $products;
    }
}


class ProductCreatedAtSorter implements SorterInterface
{

    public function sort(array $products): array
    {
        usort($products, function ($product1, $product2) {
            return date('Y-m-d', strtotime($product1['created'])) <=> date('Y-m-d', strtotime($product2['created']));
        });
        return $products;
    }
}

/**
 * a helper function to display associative array
 * @param array $array
 * @return void
 */
function displayArray(array $array): void
{
    foreach ($array as $index => $subArray) {
        echo "Item " . ($index + 1) . ":\n";
        foreach ($subArray as $key => $value) {
            echo "    " . $key . ": " . $value . "\n";
        }
        echo "\n";
    }
    echo "------------------------------- \n";
}


$products = [
    [
        'id' => 1,
        'name' => 'Alabaster Table',
        'price' => 12.99,
        'created' => '2019-01-04',
        'sales_count' => 32,
        'views_count' => 730,
    ],
    [
        'id' => 2,
        'name' => 'Zebra Table',
        'price' => 44.49,
        'created' => '2012-01-04',
        'sales_count' => 301,
        'views_count' => 3279,
    ],
    [
        'id' => 3,
        'name' => 'Coffee Table',
        'price' => 10.00,
        'created' => '2014-05-28',
        'sales_count' => 1048,
        'views_count' => 20123,
    ]
];

$productPriceSorter = new ProductPriceSorter(true);
$productSalesPerViewSorter = new ProductSalesPerViewSorter();
$productSalesCountSorter = new ProductSalesCountSorter(true);
$productCreatedAtSorter = new ProductCreatedAtSorter();

$catalog = new Catalog($products);
$productsSortedByPrice = $catalog->getProducts($productPriceSorter);
$productsSortedBySalesPerView = $catalog->getProducts($productSalesPerViewSorter);
$productsSortedBySalesCount = $catalog->getProducts($productSalesCountSorter);
$productsSortedByCreatedAt = $catalog->getProducts($productCreatedAtSorter);
displayArray($productsSortedByPrice);

//displayArray($productsSortedByCreatedAt);
