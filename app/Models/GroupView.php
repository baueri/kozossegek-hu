<?php

namespace App\Models;

use Framework\Support\StringHelper;

/**
 * Description of GroupView
 *
 * @author ivan
 */
class GroupView extends Group
{
    /**
     * Intézmény neve
     * @var string|null
     */
    public ?string $institute_name;

    /**
     * Intézményvezető neve
     *
     * @var string|null
     */
    public ?string $leader_name;

    /**
     * Lelkiségi mozgalom
     *
     * @var string|null
     */
    public ?string $spiritual_movement;

    /**
     * Város
     * @var string|null
     */
    public ?string $city;

    /**
     * Városrész
     * @var string|null
     */
    public ?string $district;

    private ?string $cachedUrl = null;

    /**
     * @return string
     */
    public function url(): string
    {
        if ($this->cachedUrl) {
            return $this->cachedUrl;
        }

        $intezmeny = StringHelper::slugify($this->institute_name);
        $varos = StringHelper::slugify($this->city);

        $data = ['varos' => $varos, 'intezmeny' => $intezmeny, 'kozosseg' => $this->slug()];

        return $this->cachedUrl = get_site_url() . route('kozosseg', $data);
    }
}
