Filters testing:
http://localhost/api/products/filter/?useId=1&priceList=&aqua&maxPrice=999200&minPrice=111&name=neko%20ime&productCategory=lolo&orderBy=price&orderType=asc

Orders testing:
POST: http://localhost/api/orders/
{
   "user_id": "1955",
   "first_name": "Petar",
   "last_name": "Ivanko",
   "email": "sstrs@gmail.com",
   "phone": "095/8152633",
   "items": [
      {
        "sku": "1072427",
        "product_name": "Product A",
        "quantity": 2,
        "price_list_name": "autem"
      },
      {
        "sku": "1008735",
        "product_name": "Product B",
        "quantity": 2,
        "price_list_name": "excepturi"
      }
    ]
}

- data is changed after tables seeding