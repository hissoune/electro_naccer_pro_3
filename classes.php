<?php
// Define DAO classes for each entity
class User
{
    private $user_id;
    private $username;
    private $email;
    private $password;
    private $role;
    private $verified;
    private $full_name;
    private $phone_number;
    private $address;
    private $disabled;
    private $city;

    // Constructor with optional parameters
    public function __construct(
        $user_id,
        $username,
        $email,
        $password,
        $role,
        $verified,
        $full_name,
        $phone_number,
        $address,
        $disabled,
        $city
    ) {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->verified = $verified;
        $this->full_name = $full_name;
        $this->phone_number = $phone_number;
        $this->address = $address;
        $this->disabled = $disabled;
        $this->city = $city;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getVerified()
    {
        return $this->verified;
    }
    public function getFullName()
    {
        return $this->full_name;
    }
    public function  getphoneNumber()
    {
        return $this->phone_number;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getDisabled()
    {
        return $this->disabled;
    }
    public function getCity()
    {
        return $this->city;
    }
    // setter methods
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }
    public function setAddress($address)
    {
        $this->address = $address;
    }
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }
    public function setCity($city)
    {
        $this->city = $city;
    }
}

class UserDAO extends BaseDAO
{
    public function authenticateUser($username, $password)
    {

        // Assuming your users table has columns 'username', 'password', 'disabled', 'verified', and 'role'
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);
        $statement->execute();

        // Check if a matching user is found
        if ($statement->rowCount() > 0) {
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Extract user data
            $disabled = $user['disabled'];
            $verified = $user['verified'];
            $role = $user['role'];

            if (!$disabled) {
                // Check if the user is verified
                if ($verified) {
                    // Store user data in the session
                    $_SESSION['user']['user_id'] = $user['user_id'];
                    $_SESSION['user']['username'] = $user['username'];
                    $_SESSION['user']['role'] = $role;

                    if ($role == 'admin') {
                        // Redirect to a dashboard for admin
                        header('Location: products.php');
                        exit();
                    } else {
                        // Redirect to index for regular user
                        header('Location: products.php');
                        exit();
                    }
                } else {
                    // Redirect to unverified page
                    header('Location: unverified.php');
                    exit();
                }
            } else {
                // Redirect to disabled page
                header('Location: disabled.php');
                exit();
            }
        } else {
            return false; // Authentication failed (no matching user)
        }
    }
    public function getAllUsers()
    {

        $query = "SELECT * FROM Users";
        $statement = $this->db->prepare($query);
        $statement->execute();

        // Fetch all users
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }
    public function addUser(User $user)
    {
        $query = "INSERT INTO Users (username, email, password, role, verified, full_name, phone_number, address, disabled, city) 
                  VALUES (:username, :email, :password, :role, :verified, :full_name, :phone_number, :address, :disabled, :city)";

        $stmt = $this->db->prepare($query);

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $role = $user->getRole();
        $verified = $user->getVerified() ? 1 : 0;
        $full_name = $user->getFullName();
        $phone_number = $user->getPhoneNumber();
        $address = $user->getAddress();
        $disabled = $user->getDisabled() ? 1 : 0;
        $city = $user->getCity();

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':verified', $verified, PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':disabled', $disabled, PDO::PARAM_INT);
        $stmt->bindParam(':city', $city);

        return $stmt->execute();
    }
    public function getUserById($userId)
    {
        $query = "SELECT * FROM Users WHERE user_id = :userId";
        $statement = $this->db->prepare($query);
        $statement->execute([':userId' => $userId]);

        // Fetch the user data as an associative array
        $userData = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if user data is fetched
        if ($userData) {
            // Create a new User object with the fetched data
            $user = new User(
                $userData['user_id'],
                $userData['username'],
                $userData['email'],
                $userData['password'],
                $userData['role'],
                $userData['verified'],
                $userData['full_name'],
                $userData['phone_number'],
                $userData['address'],
                $userData['disabled'],
                $userData['city']
            );

            return $user;
        } else {
            return null; // User not found
        }
    }

    // Method to enable a user
    public function enableUser($userId)
    {
        // Update the 'disabled' column in the database to 0 (enabled)
        $sql = "UPDATE users SET disabled = 0 WHERE user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Method to disable a user
    public function disableUser($userId)
    {
        // Update the 'disabled' column in the database to 1 (disabled)
        $sql = "UPDATE users SET disabled = 1 WHERE user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateUser(User $user)
    {
        $query = "UPDATE Users 
            SET username = :username, 
                email = :email, 
                password = :password, 
                role = :role, 
                verified = :verified, 
                full_name = :full_name, 
                phone_number = :phone_number, 
                address = :address, 
                disabled = :disabled, 
                city = :city 
            WHERE user_id = :user_id";

        $stmt = $this->db->prepare($query);

        // Assign values to variables
        $userId = $user->getUserId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $role = $user->getRole();
        $verified = $user->getVerified();
        $fullName = $user->getFullName();
        $phoneNumber = $user->getPhoneNumber();
        $address = $user->getAddress();
        $disabled = $user->getDisabled();
        $city = $user->getCity();

        // Bind variables
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword); // Password hashing
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':verified', $verified, PDO::PARAM_BOOL);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':phone_number', $phoneNumber);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':disabled', $disabled, PDO::PARAM_BOOL);
        $stmt->bindParam(':city', $city);

        return $stmt->execute();
    }

    public function getUser($username)
    {

        // Fetch user data from the database
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->execute();

        // Check if a matching user is found
        if ($statement->rowCount() > 0) {
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Return user data
            return $user;
        } else {
            return null; // User not found
        }
    }
}

class Category
{
    private $category_id;
    private $category_name;
    private $category_img;
    private $is_disabled;

    // Constructor with optional parameters
    public function __construct($category_id, $category_name, $category_img, $is_disabled = false)
    {
        $this->category_id = $category_id;
        $this->category_name = $category_name;
        $this->category_img = $category_img;
        $this->is_disabled = $is_disabled;
    }

    // Getter methods for retrieving private properties

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getCategoryName()
    {
        return $this->category_name;
    }

    public function getImagCategory()
    {
        return $this->category_img;
    }

    public function getDisabled()
    {
        return $this->is_disabled;
    }

    // Setter methods for updating private properties
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
    }
    public function setDisabled($is_disabled)
    {
        $this->is_disabled = $is_disabled;
    }
    public function isDisabled()
    {
        return $this->is_disabled;
    }
    public function setImage($category_img)
    {
        $this->category_img = $category_img;
    }
}

class CategoryDAO extends BaseDAO
{
    public function getAllCategories()
    {
        $query = "SELECT * FROM Categories"; // Replace 'categories' with your actual table name
        $statement = $this->db->prepare($query);
        $statement->execute();

        // Fetch all categories
        $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Convert the associative array to an array of Category objects
        $categoryObjects = [];
        foreach ($categories as $category) {
            $categoryObjects[] = new Category(
                $category['category_id'],
                $category['category_name'],
                $category['category_img'],
            );
        }

        return $categoryObjects;
    }
    public function addCategory(Category $category)
    {
        $query = "INSERT INTO Categories (category_name, category_img, is_disabled) 
                  VALUES (:category_name, :category_img, :is_disabled)";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':category_name', $category->getCategoryName());
        $stmt->bindParam(':category_img', $category->getImagCategory());
        $stmt->bindParam(':is_disabled', $category->getDisabled(), PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    public function updateCategory(Category $category)
    {
        $query = "UPDATE Categories SET 
                  category_name = :category_name,
                  category_img = :category_img,
                  is_disabled = :is_disabled
                  WHERE category_id = :category_id";

        $stmt = $this->db->prepare($query);

        $categoryName =  $category->getCategoryName();
        $ImagCategory = $category->getImagCategory();
        $isDisabled =  $category->getDisabled();
        $CategoryId = $category->getCategoryId();

        $stmt->bindParam(':category_name', $categoryName);
        $stmt->bindParam(':category_img', $ImagCategory);
        $stmt->bindParam(':is_disabled', $isDisabled, PDO::PARAM_BOOL);
        $stmt->bindParam(':category_id', $CategoryId);

        $stmt->execute();
    }

    public function disableCategory($categoryId)
    {

        // Update the 'disabled' status of the category
        $query = "UPDATE Categories SET is_disabled = 1 WHERE category_id = :category_id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->execute();
    }
    public function enableCategory($categoryId)
    {

        // Update the 'disabled' status of the category
        $query = "UPDATE Categories SET is_disabled = 0 WHERE category_id = :category_id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->execute();
    }
    public function getCategoryById($category_id)
    {
        $query = "SELECT * FROM Categories WHERE category_id = :category_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);
        // Check if user data is fetched
        if ($categoryData) {
            // Create a new User object with the fetched data
            $category = new Category(
                $categoryData['category_id'],
                $categoryData['category_name'],
                $categoryData['category_img'],
                $categoryData['is_disabled'],

            );

            return $category;
        } else {
            return null; // User not found
        }
    }
    public function getCategories()
    {
        $query = "SELECT * FROM Categories"; // Replace 'categories' with your actual table name
        $statement = $this->db->prepare($query);
        $statement->execute();

        // Fetch all categories
        $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Convert the associative array to an array of Category objects
        $categoryObjects = [];
        foreach ($categories as $category) {
            $categoryObjects[] = new Category(
                $category['category_id'],
                $category['category_name'],
                $category['category_img'],
                $category['is_disabled']
            );
        }

        return $categoryObjects;
    }
}
class Product
{
    private $product_id;
    private $reference;
    private $image;
    private $barcode;
    private $label;
    private $purchase_price;
    private $final_price;
    private $price_offer;
    private $description;
    private $min_quantity;
    private $stock_quantity;
    private $category_id;
    private $disabled;

    // Constructor with optional parameters
    public function __construct(
        $product_id,
        $reference,
        $image,
        $barcode,
        $label,
        $purchase_price,
        $final_price,
        $price_offer,
        $description,
        $min_quantity,
        $stock_quantity,
        $category_id,
        $disabled = false
    ) {
        $this->product_id = $product_id;
        $this->reference = $reference;
        $this->image = $image;
        $this->barcode = $barcode;
        $this->label = $label;
        $this->purchase_price = $purchase_price;
        $this->final_price = $final_price;
        $this->price_offer = $price_offer;
        $this->description = $description;
        $this->min_quantity = $min_quantity;
        $this->stock_quantity = $stock_quantity;
        $this->category_id = $category_id;
        $this->disabled = $disabled;
    }

    // Getter methods for retrieving private properties
    public function getProductId()
    {
        return $this->product_id;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getBarcode()
    {
        return $this->barcode;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getPurchasePrice()
    {
        return $this->purchase_price;
    }

    public function getFinalPrice()
    {
        return $this->final_price;
    }

    public function getPriceOffer()
    {
        return $this->price_offer;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getMinQuantity()
    {
        return $this->min_quantity;
    }

    public function getStockQuantity()
    {
        return $this->stock_quantity;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    // Setter methods for updating private properties
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }
}

class ProductDAO extends BaseDAO
{
    public function addProduct(Product $product)
    {
        $query = "INSERT INTO Products (reference, image, barcode, label, purchase_price, final_price, price_offer, description, min_quantity, stock_quantity, category_id, disabled) 
                  VALUES (:reference, :image, :barcode, :label, :purchase_price, :final_price, :price_offer, :description, :min_quantity, :stock_quantity, :category_id, :disabled)";

        $stmt = $this->db->prepare($query);

        $reference = $product->getReference();
        $image = $product->getImage();
        $barcode = $product->getBarcode();
        $label = $product->getLabel();
        $purchase_price = $product->getPurchasePrice();
        $final_price = $product->getFinalPrice();
        $price_offer = $product->getPriceOffer();
        $description = $product->getDescription();
        $min_quantity = $product->getMinQuantity();
        $stock_quantity = $product->getStockQuantity();
        $category_id = $product->getCategoryId();
        $disabled = $product->isDisabled();

        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':label', $label);
        $stmt->bindParam(':purchase_price', $purchase_price);
        $stmt->bindParam(':final_price', $final_price);
        $stmt->bindParam(':price_offer', $price_offer);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':min_quantity', $min_quantity);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':disabled', $disabled, PDO::PARAM_BOOL);

        return $stmt->execute();
    }
    public function getProductsByCategory($categoryId)
    {

        $sql = 'SELECT * FROM Products WHERE category_id = :categoryId AND disabled = FALSE';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        $products = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product(
                $row['reference'],
                $row['image'],
                $row['barcode'],
                $row['label'],
                $row['purchase_price'],
                $row['final_price'],
                $row['price_offer'],
                $row['description'],
                $row['min_quantity'],
                $row['stock_quantity'],
                $row['category_id'],
                $row['disabled']
            );


            $products[] = $product;
        }

        return $products;
    }
    public function getProductCategories()
    {
        $categories = [];

        // Replace 'Categories' with the actual name of your categories table
        $query = "SELECT DISTINCT category_name FROM Categories";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            // Fetch all unique categories from the database
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $row['category_name'];
            }
        } catch (PDOException $e) {
            // Handle database errors if needed
            echo "Error: " . $e->getMessage();
        }

        return $categories;
    }
    public function updateProduct(Product $product)
    {
        $query = "UPDATE Products SET 
                  reference = :reference,
                  image = :image,
                  barcode = :barcode,
                  label = :label,
                  purchase_price = :purchase_price,
                  final_price = :final_price,
                  price_offer = :price_offer,
                  description = :description,
                  min_quantity = :min_quantity,
                  stock_quantity = :stock_quantity,
                  category_id = :category_id,
                  disabled = :disabled
                  WHERE product_id = :product_id";

        $stmt = $this->db->prepare($query);

        $product_id = $product->getProductId(); // Assuming you have a getProductId() method
        $reference = $product->getReference();
        $image = $product->getImage();
        $barcode = $product->getBarcode();
        $label = $product->getLabel();
        $purchase_price = $product->getPurchasePrice();
        $final_price = $product->getFinalPrice();
        $price_offer = $product->getPriceOffer();
        $description = $product->getDescription();
        $min_quantity = $product->getMinQuantity();
        $stock_quantity = $product->getStockQuantity();
        $category_id = $product->getCategoryId();
        $disabled = $product->isDisabled();

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':label', $label);
        $stmt->bindParam(':purchase_price', $purchase_price);
        $stmt->bindParam(':final_price', $final_price);
        $stmt->bindParam(':price_offer', $price_offer);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':min_quantity', $min_quantity);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':disabled', $disabled, PDO::PARAM_BOOL);

        $stmt->execute();
    }



    public function disableProduct($productId)
    {

        // Update the 'disabled' status of the product
        $sql = "UPDATE products SET disabled = 1 WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function enableProduct($productId)
    {

        // Update the 'disabled' status of the product
        $sql = "UPDATE products SET disabled = 0 WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getProductById($product_id)
    {
        $query = "SELECT * FROM products WHERE product_id = :product_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
        // Check if user data is fetched
        if ($productData) {
            // Create a new User object with the fetched data
            $product = new Product(
                $productData['product_id'],
                $productData['reference'],
                $productData['image'],
                $productData['barcode'],
                $productData['label'],
                $productData['purchase_price'],
                $productData['final_price'],
                $productData['price_offer'],
                $productData['description'],
                $productData['min_quantity'],
                $productData['stock_quantity'],
                $productData['category_id'],
                $productData['disabled']

            );

            return $product;
        } else {
            return null; // User not found
        }
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM products"; // Replace 'products' with your actual table name
        $statement = $this->db->prepare($query);
        $statement->execute();

        // Fetch all products
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Convert the associative array to an array of Product objects
        $productObjects = [];
        foreach ($products as $product) {
            $productObjects[] = new Product(
                $product['product_id'],
                $product['reference'],
                $product['image'],
                $product['barcode'],
                $product['label'],
                $product['purchase_price'],
                $product['final_price'],
                $product['price_offer'],
                $product['description'],
                $product['min_quantity'],
                $product['stock_quantity'],
                $product['category_id'],
                $product['disabled']
            );
        }

        return $productObjects;
    }

    public function getProductsPaginated($offset, $productsPerPage, $categoryFilter = '')
    {
        $sql = 'SELECT * FROM products WHERE disabled = FALSE';

        // Check if a category filter is provided
        if (!empty($categoryFilter)) {
            $sql .= ' AND category_id = :categoryFilter';
        }

        $sql .= ' LIMIT :offset, :productsPerPage';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':productsPerPage', $productsPerPage, PDO::PARAM_INT);

        // Bind the category filter parameter if it is provided
        if (!empty($categoryFilter)) {
            $stmt->bindValue(':categoryFilter', $categoryFilter, PDO::PARAM_STR);
        }

        $stmt->execute();

        // Fetch results as associative array
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convert the associative array to an array of Product objects
        $productObjects = [];
        foreach ($results as $result) {
            $productObjects[] = new Product(
                $result['product_id'],
                $result['reference'],
                $result['image'],
                $result['barcode'],
                $result['label'],
                $result['purchase_price'],
                $result['final_price'],
                $result['price_offer'],
                $result['description'],
                $result['min_quantity'],
                $result['stock_quantity'],
                $result['category_id'],
                $result['disabled']
            );
        }

        return $productObjects;
    }
}


class Order
{
    private $order_id;
    private $user_id;
    private $order_date;
    private $send_date;
    private $delivery_date;
    private $total_price;
    private $order_status;

    // Constructor with optional parameters
    public function __construct($order_id, $user_id, $order_date, $send_date, $delivery_date, $total_price, $order_status = 'Pending')
    {
        $this->order_id = $order_id;
        $this->user_id = $user_id;
        $this->order_date = $order_date;
        $this->send_date = $send_date;
        $this->delivery_date = $delivery_date;
        $this->total_price = $total_price;
        $this->order_status = $order_status;
    }

    // Getter methods for retrieving private properties
    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getOrderDate()
    {
        return $this->order_date;
    }

    public function getSendDate()
    {
        return $this->send_date;
    }

    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function getOrderStatus()
    {
        return $this->order_status;
    }

    // Setter methods for updating private properties
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}

class OrderDAO extends BaseDAO
{
    public function getAllOrders()
    {
        $query = "SELECT * FROM Orders";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $orders = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order(
                $row['user_id'],
                $row['order_date'],
                $row['send_date'],
                $row['delivery_date'],
                $row['total_price'],
                $row['order_status']
            );

            // Add more properties if needed

            $orders[] = $order;
        }

        return $orders;
    }

    public function updateOrderStatus($orderId, $newStatus)
    {
        $query = "UPDATE Orders SET order_status = :new_status WHERE order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':new_status', $newStatus);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
    }
    public function addOrder(Order $order)
    {
        $query = "INSERT INTO Orders (user_id, order_date, send_date, delivery_date, total_price, order_status) 
                  VALUES (:user_id, :order_date, :send_date, :delivery_date, :total_price, :order_status)";

        $stmt = $this->db->prepare($query);
        $userid =$order->getUserId();
        $orderdat = $order->getOrderDate();
        $ordersenddat = $order->getSendDate();
        $orderdelevrydat = $order->getDeliveryDate();
        $orderpricettl = $order->getTotalPrice();
        $orderstat = $order->getOrderStatus();


        $stmt->bindParam(':user_id', $userid);
        $stmt->bindParam(':order_date',  $orderdat);
        $stmt->bindParam(':send_date',$ordersenddat);
        $stmt->bindParam(':delivery_date', $orderdelevrydat);
        $stmt->bindParam(':total_price', $orderpricettl);
        $stmt->bindParam(':order_status',$orderstat);

        // Execute the statement
        $stmt->execute();

        // Return the last inserted order ID
        return $this->db->lastInsertId();
    }

    public function updateOrder(Order $order)
    {
        $query = "UPDATE Orders SET 
                  user_id = :user_id,
                  order_date = :order_date,
                  send_date = :send_date,
                  delivery_date = :delivery_date,
                  total_price = :total_price,
                  order_status = :order_status
                  WHERE order_id = :order_id";

        $stmt = $this->db->prepare($query);
        
        $userid =$order->getUserId();
        $orderdat = $order->getOrderDate();
        $ordersenddat = $order->getSendDate();
        $orderdelevrydat = $order->getDeliveryDate();
        $orderpricettl = $order->getTotalPrice();
        $orderstat = $order->getOrderStatus();


       $stmt->bindParam(':user_id', $userid);
        $stmt->bindParam(':order_date',  $orderdat);
        $stmt->bindParam(':send_date',$ordersenddat);
        $stmt->bindParam(':delivery_date', $orderdelevrydat);
        $stmt->bindParam(':total_price', $orderpricettl);
        $stmt->bindParam(':order_status',$orderstat);

        return $stmt->execute();
    }

    public function deleteOrder($order_id)
    {
        $query = "DELETE FROM Orders WHERE order_id = :order_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);

        return $stmt->execute();
    }

    public function getOrderById($order_id)
    {
        $query = "SELECT * FROM Orders WHERE order_id = :order_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getLatestOrderId()
    {
        // Assuming 'order_id' is your auto-incremented primary key
        $query = "SELECT MAX(order_id) AS latest_order_id FROM Orders";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['latest_order_id'] ?? null;
    }

    // Add more methods as needed, such as getOrderByUser, getAllOrders, etc.
}

class OrderDetail
{
    private $order_detail_id;
    private $user_id;
    private $order_id;
    private $product_id;
    private $quantity;
    private $unit_price;
    private $total_price;

    // Constructor with optional parameters
    public function __construct($user_id, $order_id, $product_id, $quantity, $unit_price, $total_price)
    {
        $this->user_id = $user_id;
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->unit_price = $unit_price;
        $this->total_price = $total_price;
    }

    // Getter methods for retrieving private properties
    public function getOrderDetailId()
    {
        return $this->order_detail_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function getTotalPrice()
    {
        return $this->total_price;
    }

    // Setter methods for updating private properties
    public function setOrderDetailId($order_detail_id)
    {
        $this->order_detail_id = $order_detail_id;
    }
}

class OrderDetailDAO extends BaseDAO
{
    public function addOrderDetail(OrderDetail $orderDetail)
    {
        $query = "INSERT INTO OrderDetails (user_id, order_id, product_id, quantity, unit_price, total_price) 
                  VALUES (:user_id, :order_id, :product_id, :quantity, :unit_price, :total_price)";

        $stmt = $this->db->prepare($query);
        $userid =$orderDetail->getUserId();
        $orderid = $orderDetail->getOrderId();
        $prodid = $orderDetail->getProductId();
        $qantt = $orderDetail->getQuantity();
        $unitprice = $orderDetail->getUnitPrice();
        $ttlprice = $orderDetail->getTotalPrice();

        $stmt->bindParam(':user_id',  $userid);
        $stmt->bindParam(':order_id',  $orderid);
        $stmt->bindParam(':product_id',   $prodid);
        $stmt->bindParam(':quantity', $qantt);
        $stmt->bindParam(':unit_price', $unitprice);
        $stmt->bindParam(':total_price',  $ttlprice);

        return $stmt->execute();
    }

    public function updateOrderDetail(OrderDetail $orderDetail)
    {
        $query = "UPDATE OrderDetails SET 
                  user_id = :user_id,
                  order_id = :order_id,
                  product_id = :product_id,
                  quantity = :quantity,
                  unit_price = :unit_price,
                  total_price = :total_price
                  WHERE order_detail_id = :order_detail_id";

        $stmt = $this->db->prepare($query);

        $userid =$orderDetail->getUserId();
        $orderid = $orderDetail->getOrderId();
        $prodid = $orderDetail->getProductId();
        $qantt = $orderDetail->getQuantity();
        $unitprice = $orderDetail->getUnitPrice();
        $ttlprice = $orderDetail->getTotalPrice();
        $orderdetailsid = $orderDetail->getOrderDetailId();

        $stmt->bindParam(':user_id',  $userid);
        $stmt->bindParam(':order_id',  $orderid);
        $stmt->bindParam(':product_id',   $prodid);
        $stmt->bindParam(':quantity', $qantt);
        $stmt->bindParam(':unit_price', $unitprice);
        $stmt->bindParam(':total_price',  $ttlprice);
        $stmt->bindParam(':order_detail_id', $orderdetailsid);

        return $stmt->execute();
    }

    public function deleteOrderDetail($order_detail_id)
    {
        $query = "DELETE FROM OrderDetails WHERE order_detail_id = :order_detail_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_detail_id', $order_detail_id);

        return $stmt->execute();
    }

    public function getOrderDetailById($order_detail_id)
    {
        $query = "SELECT * FROM OrderDetails WHERE order_detail_id = :order_detail_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_detail_id', $order_detail_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getOrderDetailsByOrderId($order_id)
    {
        $query = "SELECT * FROM OrderDetails WHERE order_id = :order_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add more methods as needed, such as getOrderDetailsByOrderId, getAllOrderDetails, etc.
}
class ImageUploader
{
    public static function uploadImage($inputName, $targetDirectory)
    {
        // Ensure the target directory exists and is writable
        if (!is_dir($targetDirectory) || !is_writable($targetDirectory)) {
            return false; // Directory not found or not writable
        }

        $fileName = basename($_FILES[$inputName]["name"]); // Change this line
        $targetFile = $targetDirectory . '/' . uniqid('uploaded_image_') . '_' . time() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $uploadOk = 1;

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES[$inputName]["tmp_name"]); // Change this line
        if ($check === false) {
            return false; // Not an image
        }

        // Check file size (adjust as needed)
        if ($_FILES[$inputName]["size"] > 500000) { // Change this line
            return false; // File size too large
        }

        // Use a more robust method to validate image type
        $imageType = exif_imagetype($_FILES[$inputName]["tmp_name"]); // Change this line
        $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];
        if (!in_array($imageType, $allowedTypes)) {
            return false; // Unsupported file format
        }

        // Move the file to the target directory
        if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) { // Change this line
            return $targetFile; // Return the path to the uploaded image
        } else {
            return false; // Failed to move the file
        }
    }
}
