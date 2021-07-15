<?php

namespace App\Sdk\Platforms;


class FacadeMapper {
    private Mapper $mapper;
    
    /**
     * __construct
     *
     * @param  mixed $mapper
     * @return void
     */
    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function mapUser($user) {
        return $this->mapper->mapUser($user);
    }
}