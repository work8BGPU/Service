<?php

namespace App\Services;

use App\Models\Passenger\CategoryPassenger;
use App\Models\Passenger\Passenger;
use App\Models\Phone;

class PassengerService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Passenger::class);
    }

    public function createData(): array
    {
        $categories = CategoryPassenger::all('id', 'short_title');

        return [
            'categories' => $categories
        ];
    }

    public function create(array $data): Passenger
    {
        $data['category_id'] = $this->setFieldId($data['category'], CategoryPassenger::class, 'short_title');
        $data['sex'] = $data['sex']['id'];
        if ($data['CP']) $data['CP'] = $data['CP']['id'];
        else $data['CP'] = false;

        $passenger = Passenger::create($data);

        $phones = $data['phones'];
        $newPhones = [];

        foreach ($phones as $phone) {
            $newPhone = Phone::firstOrNew(['phone' => $phone['phone']]);
            $newPhone->description = $phone['description'];
            $newPhones[] = $newPhone;
        }

        $passenger->phones()->saveMany($newPhones);

        return $passenger;
    }
}
