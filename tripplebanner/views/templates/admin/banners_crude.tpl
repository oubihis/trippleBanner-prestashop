{*
    *  @author    sHKamil - Kamil Hałasa
    *  @copyright sHKamil - Kamil Hałasa
    *  @license   .l
    *}
    
    <div class="panel">
    
        <h3><i class="icon icon-comment"></i> {l s='TrippleBanner' mod='tripplebanner'}</h3>
        <h3> Total size of the images folder: {$img_folder_size}</h3>
        <form id="bannersForm"class="defaultForm form-horizontal
        {if isset($name_controller) && $name_controller} 
            {$name_controller}
        {/if}"
        {if isset($current) && $current}
            action="{$current|escape:'html':'UTF-8'}
            {if isset($token) && $token}
                &amp;token={$token|escape:'html':'UTF-8'}
            {/if}"
        {/if} 
        method="post" enctype="multipart/form-data" 
        {if isset($style)} 
            style="{$style}" 
        {/if} 
        novalidate>
            <div class="form-wrapper">
                <div class="form-group">
                    <table class="table table-hover" style="text-align: center;">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align: center;">ID</th>
                                <th scope="col" style="text-align: center;">Image</th>
                                <th scope="col" style="text-align: center;">Size</th>
                                <th scope="col" style="text-align: center;">Link</th>
                                <th scope="col" style="text-align: center;">Enable</th>
                                <th scope="col" style="text-align: center;">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach $banners as $banner}
                            <tr>
                                <td scope="row">{$banner.id_banner}</td>
                                <td><img src="{$banner.image_path}" class="banner-img" /></td>
                                <td>{$banner.img_size} MB</td>
                                <td><input type="text" name="edit_banners[{$banner.id_banner}][]" value="{$banner.link}" ></td>
                                <td>
                                    <label class="switch">
                                        <input id="{$banner.id_banner}" name="switch_banners[{$banner.id_banner}]" type="checkbox" class="switch">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <input name="delete_banners[]" value="{$banner.id_banner}" type="checkbox" class="k-checkbox">
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <button type="submit" value="1" id="submitBannersForm" name="submitBannersForm"
                        class="btn btn-default pull-right mt-md">
                        <i class="process-icon-save"></i> SAVE CHANGES
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    