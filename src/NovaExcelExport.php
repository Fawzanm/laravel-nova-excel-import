<?php

namespace Salfade\NovaExcelExport;

use Laravel\Nova\Card;

class NovaExcelExport extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/2';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'nova-excel-export';
    }

    public function requiredFields($fields)
    {
        return $this->withMeta(['required_fields' => $fields]);

    }
    public function  heading($heading){

        return $this->withMeta(['heading' => $heading]);
    }

    public function __construct($model)
    {
        parent::__construct(null);

        return $this->withMeta(['model' => $model,
            'heading'=> $this->component()
        ]);

    }


}
