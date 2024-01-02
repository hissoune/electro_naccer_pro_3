
<?php
require_once 'connex_db.php';
require_once 'users.php';

class UserDAO{
    private $db;
  
    public function __construct(){
      $this->db = Database::getInstance()->gettconnection();
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
public function get_user_by_emeil($email){
    $query= "SELECT user_id from users where email='$email'";
    $stmt = $this->db->query($query);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
}
public function get_users(){
    $query = "SELECT * FROM users ";
    $stmt = $this->db->query($query);
    $stmt -> execute();
    $usersData = $stmt->fetchAll();
    $userss = array();
    foreach ( $usersData as $usr) {
        $userss[] = new User($usr["user_id"],$usr["username"],$usr["email"], $usr["password"],$usr["role"], $usr["verified"],$usr["full_name"], $usr["phone_number"],$usr["address"], $usr["disabled"], $usr["city"]);
    }
    return $userss;

}
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
                    header('Location:products_page.php');
                    exit();
                } else {
                    // Redirect to index for regular user
                    header('Location: products_page.php');
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

public function insert_users($User){
    $query="INSERT INTO users VALUES (0, '".$User->getUsername()."','".$User-> getEmail()."', '".$User-> getPassword()."','".$User->getRole()."','".$User-> isVerified()."','".$User->getFullName()."','".$User-> getPhoneNumber()."','".$User->getAddress()."','".$User-> isDisabled()."','".$User->getCity()."') ";
    $result= $stmt = $this->db->query($query);
    
    return $result;
  



}
public function delet_user($id){
    $query = "DELETE FROM  users  WHERE user_id=" . $id ;
    $stmt = $this->db->query($query);
    $stmt -> execute();
}
public function verify_user($id){
    $query = "UPDATE users SET disabled = 1 WHERE user_id=" . $id ;
    $stmt = $this->db->query($query);
    $stmt -> execute();
}


public function updat_users( $user , $id){
    $query="UPDATE users  set user_id='".$user->getUserId()."', username='".$user-> getUsername()."',email='".$user->getEmail()."', password='".$user-> getPassword()."',       role='".$user->getRole()."', verified='".$user-> isVerified()."',full_name='".$user->getFullName()."', phone_number='".$user-> getPhoneNumber()."',      address='".$user-> getAddress()."',disabled='".$user->isDisabled()."', city='".$user-> getCity()."' WHERE user_id = '$id'"  ;
    $stmt = $this->db->query($query);
    $stmt -> execute();
}

// Add methods for insert, update, delete operations if needed
}
