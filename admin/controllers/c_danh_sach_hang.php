<?php
include ("models/m_hang_hoa.php");
class c_danh_sach_hang {
    protected $hang_hoa;
    function __construct() {
        $this->hang_hoa = new m_hang_hoa();
    }
    public function index() {
        $ds_hang_hoa = $this->hang_hoa->All_hang_hoa();


        if(isset($_SESSION["error_delete"])){
            $error_delete = $_SESSION["error_delete"];
            echo "
                    <script>alert('$error_delete')</script>
                    ";
            unset($_SESSION["error_delete"]);
        }else if(isset($_SESSION["deletes_hang_hoa"])){
            $deletes_hang_hoa = $_SESSION["deletes_hang_hoa"];
            echo "
                    <script>alert('$deletes_hang_hoa')</script>
                    ";
            unset($_SESSION["deletes_hang_hoa"]);
        }else if(isset($_SESSION["update"])){
            $update_hang_hoa = $_SESSION["update"];
            echo "
                    <script>alert('$update_hang_hoa')</script>
                    ";
            unset($_SESSION["update"]);
        }

        $view = "views/quan_li_hang/v_ds_hang_hoa.php";
        include ("templates/layout.php");

    }

    public function hang_hoa() {
        $loai_hang = $this->hang_hoa->read_loai_hang();
        $this->add_loai_hang();
        $view = "views/quan_li_hang/v_them_hang_hoa.php";
        include ("templates/layout.php");
    }

    function add_loai_hang(){
        if(isset($_POST["add_hang_hoa"])){
            $id = NULL;
            $name = $this->checkData("name_hang_hoa");
            $price = $this->checkData("Price");
            $sale = $this->checkData("sale");
            $image = $this->uploadImage("image_hang_hoa","../public/layout1/img_products/");
            $ngay_nhap = $this->checkData("date");
            $id_loai_hang = $this->checkData("type_products");
            $dac_biet = $this->checkData("dac_biet");
            $mo_ta = $this->checkData("mo_ta");
            $so_luot_xem = $this->checkData("live");

            $result_add = $this->hang_hoa->add_hang_hoa($id,$name,$price,$sale,$image,$id_loai_hang,$dac_biet,$ngay_nhap,$so_luot_xem,$mo_ta);

            if($result_add){
                echo " 
                    <script>alert('Th??m M???t h??ng Th??nh c??ng!')</script>
                    ";
            }else {
                echo " 
                    <script>alert('Th??m M???t h??ng Th???t b???i!')</script>
                    ";
            }


        }
    }

    public function edit_hang_hoa() {
        $id = $_GET['id'];
        $loai_hang = $this->hang_hoa->read_loai_hang();
        if(!empty($id)){
            $hang_hoa = $this->hang_hoa->One_row_hang_hoa($id);
            if(isset($_POST["up_hang_hoa"])){
                $name_up = $this->checkData("ten_hang");
                $price = $this->checkData("up_price");
                $sale = $this->checkData("up_sale");
                $image = $this->uploadImage("up_image","../public/layout1/img_products/");
                $ngay_nhap = $this->checkData("up_date");
                $id_loai_hang = $this->checkData("up_type_hang_hoa");
                $dac_biet = $this->checkData("up_dac_biet");
                $mo_ta = $this->checkData("up_mo_ta");
                $so_luot_xem = $this->checkData("up_live");
                $result_up_hang_hoa = $this->hang_hoa->update_hang_hoa($id,$name_up,$price,$sale,$image,$id_loai_hang,$dac_biet,$ngay_nhap,$so_luot_xem,$mo_ta);
                if($result_up_hang_hoa){
                    $_SESSION["update"] = "Update Th??nh c??ng!";
                    header("Location:ds_hang.php");
                }else {
                    echo " 
                    <script>alert('Update Th???t b???i!')</script>
                    ";
                }
            }
            if(empty($loai_hang)) {
                $_SESSION["update_error"] = "M???t h??ng kh??ng t???n t???i";
                header("Location:ds_hang.php");
            }

        }
        $view = "views/quan_li_hang/v_edit_hang_hoa.php";
        include ("templates/layout.php");
    }

    public function delete_hang_hoa(){
        $id = $_GET["id"] ;
        if(!empty($id)){
            $delete = $this->hang_hoa->delete_hang_hoa($id);
            if($delete){
                $_SESSION["delete"] = "X??a th??nh c??ng!";
                header("Location:ds_loai_hang.php");
            }else {
                echo " 
                    <script>alert('Delete Th???t b???i!')</script>
                    ";
            }
        }
    }

    //    X??a c??c lo???i h??ng ???????c t??ch
    public function deletes_hang_hoa(){
        $id = $_GET["id"] ;
        if(!empty($id)){
            $ids = explode(',',$id);
            $delete = NULL;
            foreach($ids as $value) {
                $delete = $this->hang_hoa->delete_hang_hoa($value);
            }
            if($delete){
                $_SESSION["deletes_hang_hoa"] = "X??a th??nh c??ng " . count($ids) . " H??ng h??a!" ;
                header("Location:ds_hang.php");
            }else {
                echo "
                    <script>alert('Delete Th???t b???i!')</script>
                    ";
            }
        }else {
            $_SESSION["error_delete"] = "Vui L??ng ch???n s???n ph???m c???n x??a";
            header("Location:ds_hang.php");
        }
    }

//upload ???nh tr??? v??? t??n ???nh
    function uploadImage($imageCheck,$namesource){
        if($_FILES["{$imageCheck}"]['error'] == 0){
            $nameImage = ($_FILES["{$imageCheck}"]['error'] == 0) ? uniqid('product-', true)
                . '.' . strtolower(pathinfo($_FILES["{$imageCheck}"]["name"], PATHINFO_EXTENSION)) :"";
            if ($nameImage != "") {
                //di chuy???n h??nh ???nh v??o th?? m???c source
                move_uploaded_file($_FILES["{$imageCheck}"]['tmp_name'],"{$namesource}".$nameImage);
            }
            echo "<script>alert('upload th??nh c??ng')</script>";
            return $nameImage;
        }else {
            return "";
        }

    }

    function checkData($name_post){
        $namepost = $_POST["{$name_post}"] == "" ? "" : $_POST["{$name_post}"];
        return $namepost;
    }
}