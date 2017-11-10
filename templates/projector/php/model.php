<?php

namespace Modules\{$module}\Models;

use Phact\Orm\Model;
{foreach $uses as $use}use {$use};
{/foreach}


class {$name} extends Model
{
{foreach $constants as $constant}
    const {$constant['name']} = {if $.php.is_numeric($constant['value'])}{$constant['value']}{else}"{$constant['value']}"{/if};
{/foreach}

    public static function getFields() 
    {
        return [
{foreach $fields as $field}
            "{$field['name']}" => [
                'class' => {$field['type']}::class,
{if $field['label']}
                'label' => "{$field['label']}",
{/if}
{if !$field['editable']}
                'editable' => false,
{/if}
{if $field['null']}
                'null' => true,
{/if}
{if $field['hint']}
                'hint' => "{$field['hint']}",
{/if}
{if $field['choices']}
                'choices' => [
{foreach $field['choices'] as $choice}
                    self::{$choice['name']} => "{$choice['label']}",
{/foreach}
                ],
{/if}
{if $field['attributesClean']}
{foreach $field['attributesClean'] as $attributeName => $value}
                "{$attributeName}" => {if $.php.is_numeric($value) || $attributeName == 'modelClass'}{$value}{else}"{$value}"{/if},
{/foreach}
{/if}
            ],
{/foreach}
        ];
    }

{if $toString}
    public function __toString() 
    {
        return (string) $this->{$toString};
    }
{/if}
} 