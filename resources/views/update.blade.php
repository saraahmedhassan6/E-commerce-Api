<form action="{{route('TestUpdate',['id' => $product->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('put')
    <h1>Update Test Category Cloudinary</h1>
    <input type="file" name="images[]" multiple><br>
    <input type="text" name="name" value="{{$product->name}}" style="border:1px solid black">
    <input type="number" name="price" value="{{$product->price}}" style="border:1px solid black"><br>
    <input type="number" name="amount" value="{{$product->amount}}" style="border:1px solid black"><br>
    <input type="number" name="discount" value="{{$product->discount}}" style="border:1px solid black"><br>
    <input type="number" name="brand_id" value="{{$product->brand_id}}" style="border:1px solid black"><br>
    <input type="number" name="category_id" value="{{$product->category_id}}" style="border:1px solid black"><br>
    <input type="radio" name="is_trendy" value=0 {{$product->is_trendy == 0 ? "checked":""}}>
    <input type="radio" name="is_trendy" value=1 {{$product->is_trendy == 1 ? "checked":""}}>

    <input type="radio" name="is_available" value=0 {{$product->is_available == 0 ? "checked":""}}>
    <input type="radio" name="is_available" value=1 {{$product->is_available == 1 ? "checked":""}}>
    <button type="submit">submit</button>
</form>
