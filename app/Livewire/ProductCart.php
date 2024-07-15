<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Modules\Product\Entities\Product;

class ProductCart extends Component
{

    public $listeners = ['productSelected', 'discountModalRefresh'];

    public $cart_instance;
    public $total_subtotal = 0;

    public $quantity;
    public $check_quantity;
    public $unit_price;
    public $data;

    private $product;

    public function mount($cartInstance, $data = null) {
        $this->cart_instance = $cartInstance;

        if ($data) {
            $this->data = $data;

            $cart_items = Cart::instance($this->cart_instance)->content();

            foreach ($cart_items as $cart_item) {
                $this->check_quantity[$cart_item->id] = [$cart_item->options->stock];
                $this->quantity[$cart_item->id] = $cart_item->qty;
                $this->unit_price[$cart_item->id] = $cart_item->price;

                if ($cart_item->options->product_discount_type == 'fixed') {
                    $this->item_discount[$cart_item->id] = $cart_item->options->product_discount;
                } elseif ($cart_item->options->product_discount_type == 'percentage') {
                    $this->item_discount[$cart_item->id] = round(100 * ($cart_item->options->product_discount / $cart_item->price));
                }

                $this->total_subtotal += $cart_item->options->sub_total;
            }
        } else {
            $this->check_quantity = [];
            $this->quantity = [];
            $this->unit_price = [];
        }
    }

    public function render() {
        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.product-cart', [
            'cart_items' => $cart_items
        ]);
    }

    public function productSelected($product) {
        $cart = Cart::instance($this->cart_instance);

        $exists = $cart->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product['id'];
        });

        if ($exists->isNotEmpty()) {
            session()->flash('message', 'Product exists in the cart!');

            return;
        }

        $this->product = $product;

        $cart->add([
            'id'      => $product['id'],
            'name'    => $product['product_name'],
            'qty'     => 1,
            'price'   => $this->calculate($product)['price'],
            'weight'  => 1,
            'options' => [
                'sub_total'             => $this->calculate($product)['sub_total'],
                'code'                  => $product['product_code'],
                'stock'                 => $product['product_quantity'],
                'unit_price'            => $this->calculate($product)['unit_price']
            ]
        ]);

        $this->check_quantity[$product['id']] = $product['product_quantity'];
        $this->quantity[$product['id']] = 1;

        $this->total_subtotal += $this->calculate($product)['sub_total'];
    }

    public function removeItem($row_id) {
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);
        $this->total_subtotal -= $cart_item->options->sub_total;

        Cart::instance($this->cart_instance)->remove($row_id);
    }

    public function updateQuantity($row_id, $product_id) {
        if  ($this->cart_instance == 'sale' || $this->cart_instance == 'purchase_return') {
            if ($this->check_quantity[$product_id] < $this->quantity[$product_id]) {
                session()->flash('message', 'The requested quantity is not available in stock.');
                return;
            }
        }

        //Cart::instance($this->cart_instance)->update($row_id, $this->quantity[$product_id]);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        // Hitung subtotal baru berdasarkan kuantitas yang diperbarui
        $new_sub_total = $cart_item->price * $this->quantity[$product_id];

        // Sesuaikan total_subtotal
        $this->total_subtotal -= $cart_item->options->sub_total;
        $this->total_subtotal += $new_sub_total;

        Cart::instance($this->cart_instance)->update($row_id, $this->quantity[$product_id]);

        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                //'sub_total'             => $cart_item->price * $cart_item->qty,
                'sub_total'             => $new_sub_total,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit_price'            => $cart_item->options->unit_price,
            ]
        ]);
    }


    public function updatePrice($row_id, $product_id) {
        $product = Product::findOrFail($product_id);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        $new_price = $this->unit_price[$product['id']];
        $new_sub_total = $this->calculate($product, $new_price)['sub_total'];

        // Sesuaikan total_subtotal
        $this->total_subtotal -= $cart_item->options->sub_total;
        $this->total_subtotal += $new_sub_total;

        //Cart::instance($this->cart_instance)->update($row_id, ['price' => $this->unit_price[$product['id']]]);
        Cart::instance($this->cart_instance)->update($row_id, ['price' => $new_price]);

        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                //'sub_total'             => $this->calculate($product, $this->unit_price[$product['id']])['sub_total'],
                'sub_total'             => $new_sub_total,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                //'unit_price'            => $this->calculate($product, $this->unit_price[$product['id']])['unit_price'],
                'unit_price'            => $this->calculate($product, $new_price)['unit_price'],
            ]
        ]);
    }

    public function calculate($product, $new_price = null) {
        if ($new_price) {
            $product_price = $new_price;
        } else {
            $this->unit_price[$product['id']] = $product['product_price'];
            if ($this->cart_instance == 'purchase' || $this->cart_instance == 'purchase_return') {
                $this->unit_price[$product['id']] = $product['product_cost'];
            }
            $product_price = $this->unit_price[$product['id']];
        }

        $price = 0;
        $unit_price = 0;
        $sub_total = 0;

        $price = $product_price;
        $unit_price = $product_price;
        $sub_total = $product_price;

        return [
            'price' => $price, 
            'unit_price' => $unit_price, 
            'sub_total' => $sub_total
        ];
    }

     public function updateCartOptions($row_id, $product_id, $cart_item) {
         Cart::instance($this->cart_instance)->update($row_id, ['options' => [
             'sub_total'             => $cart_item->price * $cart_item->qty,
             'code'                  => $cart_item->options->code,
             'stock'                 => $cart_item->options->stock,
             'unit_price'            => $cart_item->options->unit_price,
         ]]);
    }
}