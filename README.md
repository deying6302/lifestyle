## SQL command

(1) Đoạn mã sau đây trình bày cách truy xuất số lượng sản phẩm được liên kết với từng màu bằng cách sử dụng Eloquent và SQL thô.

### Eloquent Query

```php (1)
$colors = Color::withCount('products')->get();
```

### Raw SQL Query

```sql (1)
SELECT colors.*, COUNT(product_color.product_id) as products_count
FROM colors
LEFT JOIN product_color ON colors.id = product_color.color_id
GROUP BY colors.id;
```
