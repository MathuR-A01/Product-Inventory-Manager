<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Path to the JSON data file within the storage directory.
     */
    private string $dataFile = 'products.json';

    /**
     * Display the main product inventory page.
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Fetch all products as JSON, ordered by datetime submitted.
     */
    public function list()
    {
        $products = $this->readData();

        // Sort by datetime_submitted ascending
        usort($products, function ($a, $b) {
            return strtotime($a['datetime_submitted']) - strtotime($b['datetime_submitted']);
        });

        // Calculate grand total
        $grandTotal = array_sum(array_column($products, 'total_value'));

        return response()->json([
            'success' => true,
            'products' => $products,
            'grand_total' => round($grandTotal, 2),
        ]);
    }

    /**
     * Store a new product entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity_in_stock' => 'required|numeric|min:0',
            'price_per_item' => 'required|numeric|min:0',
        ]);

        $products = $this->readData();

        $quantity = floatval($validated['quantity_in_stock']);
        $price = floatval($validated['price_per_item']);

        $product = [
            'id' => Str::uuid()->toString(),
            'product_name' => $validated['product_name'],
            'quantity_in_stock' => $quantity,
            'price_per_item' => $price,
            'datetime_submitted' => now()->format('Y-m-d H:i:s'),
            'total_value' => round($quantity * $price, 2),
        ];

        $products[] = $product;
        $this->writeData($products);

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully.',
            'product' => $product,
        ], 201);
    }

    /**
     * Update an existing product entry.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity_in_stock' => 'required|numeric|min:0',
            'price_per_item' => 'required|numeric|min:0',
        ]);

        $products = $this->readData();
        $found = false;

        foreach ($products as &$product) {
            if ($product['id'] === $id) {
                $quantity = floatval($validated['quantity_in_stock']);
                $price = floatval($validated['price_per_item']);

                $product['product_name'] = $validated['product_name'];
                $product['quantity_in_stock'] = $quantity;
                $product['price_per_item'] = $price;
                $product['total_value'] = round($quantity * $price, 2);
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $this->writeData($products);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
        ]);
    }

    /**
     * Delete a product entry.
     */
    public function destroy(string $id)
    {
        $products = $this->readData();
        $filtered = array_values(array_filter($products, fn($p) => $p['id'] !== $id));

        if (count($filtered) === count($products)) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $this->writeData($filtered);

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    /**
     * Read products from the JSON file.
     */
    private function readData(): array
    {
        if (!Storage::disk('local')->exists($this->dataFile)) {
            return [];
        }

        $content = Storage::disk('local')->get($this->dataFile);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Write products to the JSON file with pretty print.
     */
    private function writeData(array $data): void
    {
        Storage::disk('local')->put(
            $this->dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
