<?php
session_start();
$pagetitle = 'New Ads';
include "init.php";
if (isset($_SESSION['user'])){
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors = array();
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $cat = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

        if (empty($formErrors)) {

            //insert into database with this info
            $stmt = $con->prepare("INSERT INTO items(name , description , price , country_made , status , cat_id , member_id , add_date) 
                                                     VALUES(:name , :desc , :price , :country , :status , :cat , :mem , now())");
            $stmt->execute(array(
                'name' => $name,
                'desc' => $desc,
                'price' => $price,
                'country' => $country,
                'status' => $status,
                'cat' => $cat,
                'mem' => $_SESSION['id']
            ));
            if ($stmt){
                $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted </div>';
                redirectHome($themsg, 'back');
            }
        }
    }
    ?>

    <h1 class="text-center">Create Ad</h1>

    <div class="ads">
        <div class="container">
            <div class="row">
                <div class="col-md-8">

                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

                        <div class="form-group row">
                            <label class="col-sm-2">Name</label>
                            <div class="col-sm-6">
                                <input type="text" name="name" class="form-control live-name" required="required" data-class=".live-title">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Description</label>
                            <div class="col-sm-6">
                                <input type="text" name="description" class="form-control live-desc" data-class=".live-desc">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Price</label>
                            <div class="col-sm-6">
                                <input type="text" name="price" class="form-control live-price" required="required" data-class=".live-price">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Country</label>
                            <div class="col-sm-6">
                                <input type="text" name="country" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Status</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="status" required>
                                    <option value="">...</option>
                                    <option value="1">new</option>
                                    <option value="2">like new</option>
                                    <option value="3">used</option>
                                    <option value="4">old</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Category</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="category">
                                    <option value="">...</option>
                                    <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach ($cats as $cat){
                                        echo "<option value='".$cat['id']."'>".$cat['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-10">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>

                    </form>

                </div>
                <div class="col-md-4 live-prev">
                    <span class="live-price"></span>
                    <img src='' alt=''>
                    <div class='caption'>
                       <h3 class="live-title">test</h3>
                        <p class="live-desc">desc</p>
                        </div>
                </div>
            </div>
            <!-- errors -->
            <?php
              if (!empty($formErrors)){
                  foreach ($formErrors as $error){
                      echo "<div class='alert alert-danger'></div>". $error . "<br>";
                  }
              }
            ?>
        </div>
    </div>


    <?php
}else{
    header('Location:login.php');
    exit();
}
include $tpl."footer.php";

