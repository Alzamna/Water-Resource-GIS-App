<?php

namespace App\Models;

use CodeIgniter\Model;

class WaterResourceModel extends Model
{
    protected $table = 'water_resources';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'type',
        'latitude',
        'longitude',
        'description',
        'status',
        'photo',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'type' => 'required|in_list[deep-well,reservoir,drainage,irrigation,other]',
        'latitude' => 'required|decimal',
        'longitude' => 'required|decimal',
        'status' => 'required|in_list[active,maintenance,inactive]',
        'description' => 'permit_empty|max_length[1000]',
        'photo' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama lokasi harus diisi',
            'min_length' => 'Nama lokasi minimal 3 karakter',
            'max_length' => 'Nama lokasi maksimal 255 karakter'
        ],
        'type' => [
            'required' => 'Jenis infrastruktur harus dipilih',
            'in_list' => 'Jenis infrastruktur tidak valid'
        ],
        'latitude' => [
            'required' => 'Latitude harus diisi',
            'decimal' => 'Latitude harus berupa angka desimal'
        ],
        'longitude' => [
            'required' => 'Longitude harus diisi',
            'decimal' => 'Longitude harus berupa angka desimal'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status tidak valid'
        ],
        'description' => [
            'max_length' => 'Deskripsi maksimal 1000 karakter'
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
     * Get locations by type
     */
    public function getByType($type)
    {
        return $this->where('type', $type)->findAll();
    }

    /**
     * Get active locations
     */
    public function getActiveLocations()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get locations within radius
     */
    public function getLocationsWithinRadius($lat, $lng, $radius = 10)
    {
        // Using Haversine formula to calculate distance
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) AS distance 
                FROM {$this->table} 
                HAVING distance < ? 
                ORDER BY distance";
        
        return $this->db->query($sql, [$lat, $lng, $lat, $radius])->getResultArray();
    }

    /**
     * Get statistics by type
     */
    public function getStatistics()
    {
        $builder = $this->db->table($this->table);
        $builder->select('type, COUNT(*) as count');
        $builder->groupBy('type');
        
        $results = $builder->get()->getResultArray();
        
        $stats = [];
        foreach ($results as $result) {
            $stats[$result['type']] = $result['count'];
        }
        
        return $stats;
    }

    /**
     * Search locations
     */
    public function searchLocations($keyword, $type = null, $status = null)
    {
        $builder = $this->builder();
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('name', $keyword)
                    ->orLike('description', $keyword)
                    ->groupEnd();
        }
        
        if (!empty($type)) {
            $builder->where('type', $type);
        }
        
        if (!empty($status)) {
            $builder->where('status', $status);
        }
        
        return $builder->get()->getResultArray();
    }
}
