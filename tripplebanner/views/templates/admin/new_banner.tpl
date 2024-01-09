{*
    * @author sHKamil - Kamil Hałasa
    * @copyright sHKamil - Kamil Hałasa
    * @license .l
    *}
    
    <div class="panel">
    
        <h3><i class="icon icon-comment"></i> Add new banner </h3>
        <form id="newBannerForm"
            class="defaultForm form-horizontal{if isset($name_controller) && $name_controller} {$name_controller}{/if}" {if
            isset($current) && $current}
            action="{$current|escape:'html':'UTF-8'}{if isset($token) && $token}&amp;token={$token|escape:'html':'UTF-8'}{/if}"
            {/if} method="post" enctype="multipart/form-data" {if isset($style)} style="{$style}" {/if} novalidate>
            
            <div class="form-wrapper">
                <div class="form-group">
                    <label class="control-label col-lg-4">
                        Banner image:
                    </label>
                    <div class="col-lg-8">
                        <input type="file" name="new_banner_img" id="NEW_BANNER_IMG">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">
                        Link:
                    </label>
                    <div class="col-lg-8">
                        <input type="text" name="new_link" id="NEW_LINK">
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" value="1" id="submitNewBannerForm" name="submitNewBannerForm" class="btn btn-default pull-right mt-md">
                    <i class="process-icon-save"></i> ADD NEW BANNER
                </button>
            </div>
        </form>
    </div>
    