<?php

class ProductsController{
    public function getAllProducts(){
        $products = Product::getAll();
        return $products;
    }
    public function getProductsByCategory($id){
        if(isset($id)){
            $data = array(
                'id' => $id
            );
            $products = Product::getProductByCat($data);
            return $products;
        }
    }
    public function getProductsTrending(){
            $products = Product::getProductTrending();
            return $products;
    }
     public function getProduct(){
        if(isset($_POST["product_id"])){
            $data = array(
                'id' => $_POST["product_id"]
            );
            $product = Product::getProductById($data);
            return $product;
        }
    }
    public function emptyCart($id,$price){
        unset($_SESSION["products_".$id]);
        $_SESSION["count"] -= 1;
        $_SESSION["totaux"] -= $price;
        Redirect::to("cart");
    }
    public function newProduct(){
        if(isset($_POST["submit"])){
            $data = array(
                "product_title" => $_POST["product_title"],
                "product_description" => $_POST["product_description"],
                "product_image" => $this->uploadPhoto(),
                "product_price" => $_POST["product_price"],
                "product_category_id" => $_POST["product_category_id"],
            );
            $result = Product::addProduct($data);
            if ($result === 'exist'){
                Session::set("error","Produit Already exist");
                Redirect::to("products");
            }
            elseif($result === "ok"){
                Session::set("success","Produit Added successfully");
                Redirect::to("products");
            }else {
                echo $result;
            }
        }
    }
    public function updateProduct(){
        if(isset($_POST["submit"])){
            $oldImage = $_POST["product_current_image"];
            $data = array(
                "product_id" => $_POST["product_id"],
                "product_title" => $_POST["product_title"],
                "product_description" => $_POST["product_description"],
                "product_image" => $this->uploadPhoto($oldImage),
                "product_price" => $_POST["product_price"],
                "product_category_id" => $_POST["product_category_id"],
            );
            $result = Product::editProduct($data);
            if($result === "ok"){
                Session::set("success","Produit modifié");
                Redirect::to("products");
            }else{
                echo $result;
            }
        }
    }
    public function uploadPhoto($oldImage = null){
        $dir = "public/uploads";
        $time = time();
        $name = str_replace(' ','-',strtolower($_FILES["image"]["name"]));
        $type = $_FILES["image"]["type"];
        $ext = substr($name,strpos($name,'.'));
        $ext = str_replace('.','',$ext);
        $name = preg_replace("/\.[^.\s]{3,4}$/", "",$name);
        $imageName = $name.'-'.$time.'.'.$ext;
        if(move_uploaded_file($_FILES["image"]["tmp_name"],$dir."/".$imageName)){
            return $imageName;
        }
        return $oldImage;
    }
    public function removeProduct(){
        if(isset($_POST["product_id"])){
            $data = array(
                "id" => $_POST["product_id"]
            );
            $result = Product::deleteProduct($data);
            if($result === "ok"){
                Session::set("success","Product has been delete");
                Redirect::to("products");
            }else{
                echo $result;
            }
        }
    }
    public function trendProduct(){
        if(isset($_POST["trend_product_id"])){
            $data = array(
                "id" => $_POST["trend_product_id"]
            );
            $result = Product::trendProduct($data);
            if($result === "ok"){
                Session::set("success","Product has been trended");
                Redirect::to("products");
            }else{
                echo $result;
            }
        }
    }
}