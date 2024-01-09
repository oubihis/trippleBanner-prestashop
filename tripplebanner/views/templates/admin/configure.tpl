{*
    * @author sHKamil - Kamil Hałasa
    * @copyright sHKamil - Kamil Hałasa
    * @license .l
    *}
    
    {include file="module:tripplebanner/views/templates/admin/new_banner.tpl"}
    
    {if isset($banners) && !empty($banners)}
        {include file="module:tripplebanner/views/templates/admin/banners_crude.tpl"}
    {/if}
    