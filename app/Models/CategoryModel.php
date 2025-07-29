<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'description', 
        'color',
        'icon',
        'is_active',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]|is_unique[categories.name,id,{id}]',
        'description' => 'permit_empty|max_length[1000]',
        'color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        'icon' => 'permit_empty|max_length[50]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama kategori harus diisi',
            'min_length' => 'Nama kategori minimal 3 karakter',
            'max_length' => 'Nama kategori maksimal 255 karakter',
            'is_unique' => 'Nama kategori sudah digunakan'
        ],
        'description' => [
            'max_length' => 'Deskripsi maksimal 1000 karakter'
        ],
        'color' => [
            'regex_match' => 'Format warna harus berupa hex code (contoh: #FF0000)'
        ],
        'icon' => [
            'max_length' => 'Icon maksimal 50 karakter'
        ],
        'is_active' => [
            'in_list' => 'Status harus berupa 0 atau 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get active categories
     */
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Get categories with pagination and search
     */
    public function getCategoriesWithPagination($search = '', $limit = 10, $offset = 0)
    {
        $builder = $this->builder();
        
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('name', $search)
                   ->orLike('description', $search)
                   ->groupEnd();
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Count categories with search
     */
    public function countCategoriesWithSearch($search = '')
    {
        $builder = $this->builder();
        
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('name', $search)
                   ->orLike('description', $search)
                   ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    /**
     * Check if category can be deleted (not used by other entities)
     */
    public function canDelete($id)
    {
        // Check if category is used by water resources or other entities
        $db = \Config\Database::connect();
        
        // Check water_resources table if it has category_id column
        if ($db->tableExists('water_resources')) {
            $query = $db->query("SHOW COLUMNS FROM water_resources LIKE 'category_id'");
            if ($query->getNumRows() > 0) {
                $count = $db->table('water_resources')
                           ->where('category_id', $id)
                           ->countAllResults();
                if ($count > 0) {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Get category statistics
     */
    public function getCategoryStats()
    {
        try {
            $total = $this->countAll();
            $active = $this->where('is_active', 1)->countAllResults(false);
            $inactive = $this->where('is_active', 0)->countAllResults(false);
            
            return [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive
            ];
        } catch (\Exception $e) {
            log_message('error', 'CategoryModel getCategoryStats error: ' . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0
            ];
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }
        
        $newStatus = $category['is_active'] ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Get categories for dropdown/select options
     */
    public function getCategoriesForSelect()
    {
        $categories = $this->getActiveCategories();
        $options = [];
        
        foreach ($categories as $category) {
            $options[$category['id']] = $category['name'];
        }
        
        return $options;
    }
}
