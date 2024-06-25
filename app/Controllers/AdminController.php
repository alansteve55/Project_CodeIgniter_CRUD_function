<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use Faker\Extension\Helper;
use App\Models\Category;
use SSP;
use Mberecall\CodeIgniter\Library\Slugify;
use App\Models\Products;
use Mberecall\CI_Slugify\SlugService;

class AdminController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];
    protected $db;

    public function __construct()
    {
        require_once APPPATH . 'ThirdParty/ssp.php';
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];
        return view('backend/pages/home', $data);
    }

    public function logoutHandler()
    {
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('fail', 'You are logged out');
    }

    public function profile()
    {
        $data = array(
            'pageTitle' => 'Profile',
        );
        return view('backend/pages/profile', $data);
    }

    public function categories()
    {
        $data = [
            'pageTitle' => 'categories',
        ];
        return view('backend/pages/categories', $data);
    }

    public function addCategory()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'category_name' => [
                    'rules' => 'required|is_unique[categories.label]',
                    'errors' => [
                        'required' => 'Category label is required.',
                        'is_unique' => 'Category lable name is already exists.'
                    ]
                ]
            ]);
            if ($validation->run() === FALSE) {
                $errors = $validation->getErrors();
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors]);
            } else {
                // return $this->response->setJSON(['status'=>1, 'token'=>csrf_hash(), 'msg'=>'Validated...']);
                $category = new Category();
                $save = $category->save(['label' => $request->getVar('category_name')]);

                if ($save) {
                    return $this->response->setJSON(['status' => 1, 'token' => csrf_hash(), 'msg' => 'New category added successfully.']);
                } else {
                    return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'msg' => 'Something went wrong.']);
                }
            }
        }
    }

    // Add category
    public function getCategories()
    {
        //DB Details
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db"   => $this->db->database
        );

        $table = "categories";
        $primaryKey = "id";
        $columns = array(
            array(
                "db" => "id",
                "dt" => 0
            ),
            array(
                "db" => "label",
                "dt" => 1
            ),
            array(
                "db" => "id",
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    return "<div class = 'btn-group'>
                        <button class = 'btn btn-sm btn-link p-0 mx-1 editCategoryBtn' data-id = '" . $row['id'] . "'>Edit</button>
                        <button class = 'btn btn-sm btn-link p-0 mx-1 deleteCategoryBtn' data-id = '" . $row['id'] . "'>Delete</button>
                    </div>";
                }
            ),
            array(
                "db" => "ordering",
                "dt" => 3
            ),
        );
        return json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    // Edit category
    public function getCategory()
    {

        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('category_id');
            $category = new Category();
            $category_data = $category->find($id);
            return $this->response->setJSON(['data' => $category_data]);
        }
    }

    public function updatecategory()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('category_id');
            $validation = \Config\Services::validation();

            $this->validate([
                'category_name' => [
                    'rules' => 'required|is_unique[categories.label,id, ' . $id . ']',
                    'errors' => [
                        'required' => 'Category label is required.',
                        'is_unique' => 'Category lable name is already exists.'
                    ]
                ]
            ]);
            if ($validation->run() === FALSE) {
                $errors = $validation->getErrors();
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors]);
            } else {
                // return $this->response->setJSON(['status'=>1, 'token'=>csrf_hash(), 'msg'=>'Validated...']);
                $category = new Category();
                $update = $category->where('id', $id)
                    ->set(['label' => $request->getVar('category_name')])
                    ->update();

                if ($update) {
                    return $this->response->setJSON(['status' => 1, 'token' => csrf_hash(),  'msg' => 'Category updated successfully.']);
                } else {
                    return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(),  'msg' => 'Something went wrong.']);
                }
            }
        }
    }

    //Delete category
    public function deleteCategory()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('category_id');
            $category = new Category();
            $delete = $category->delete($id);

            if ($delete) {
                return $this->response->setJSON(['status' => 1, 'msg' => 'Category has been successfully deleted.']);
            } else {
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(),  'msg' => 'Something went wrong.']);
            }
        }
    }

    //Add product
    public function getParentCategories()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('parent_category_id');
            $options = '<option value="0">Uncategorized</option>';
            $category = new Category();
            $parent_categories = $category->findAll();

            if (count($parent_categories)) {
                $added_options = '';
                foreach ($parent_categories as $parent_category) {
                    $isSelected = $parent_category['id'] == $id ? 'selected' : '';
                    $added_options .= '<option value="' . $parent_category['id'] . '" ' . $isSelected . '>' . $parent_category['label'] . '</option>';
                }
                $options = $options . $added_options;
                return $this->response->setJSON(['status' => 1, 'data' => $options]);
            } else {
                return $this->response->setJSON(['status' => 1, 'data' => $options]);
            }
        }
    }  
}
