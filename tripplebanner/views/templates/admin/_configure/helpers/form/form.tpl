{*
    *  @author    sHKamil - Kamil Hałasa
    *  @copyright sHKamil - Kamil Hałasa
    *  @license   .l
    *}
    
   {extends file="helpers/form/form.tpl"}
   {block name="field"}
    <div class="col-lg-8">
        {if $input.name === 'TRIPPLEBANNER_IMG_1' ||  $input.name === 'TRIPPLEBANNER_IMG_2' ||  $input.name === 'TRIPPLEBANNER_IMG_3'}
            <div class="form-group">
                <div class="col-lg-6">
                    <input id="{$input.name}" type="{$input.type}" class="hide" name="{$input.name}"/>
                    <div class="dummyfile input-group">
                        <span class="input-group-addon"><i class="icon-file"></i></span>
                        <input id="{$input.name}-name" type="text" value="{$fields_value[$input.name]}" name="{$input.name}" readonly />
                        <span class="input-group-btn">
                            <button id="{$input.name}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                <i class="icon-folder-open"></i> Add file
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc}
                </p>
            {/if}
            <div class="form-group">
                {if isset($fields_value[$input.name]) && $fields_value[$input.name] != ''}
                    <p class="help-block">
                        Path: {$fields_value[$input.name]}
                    </p>
                {/if}
                {$file_name = end(explode("/",$fields_value[$input.name]))}
                {if isset($fields_value[$input.name]) && $fields_value[$input.name] !== '' && $input.name[strlen($input.name)-1] > 0 && $input.name[strlen($input.name)-1] <= 3}
                    {if  $input.name === 'TRIPPLEBANNER_IMG_1' ||  $input.name === 'TRIPPLEBANNER_IMG_2' ||  $input.name === 'TRIPPLEBANNER_IMG_3'}
                        <div class="checkbox">
                            <label>
                                <input name="delete_images[]" value="{$input.name}" type="checkbox">
                                Delete image.
                            </label>
                        </div>
                        <img src="/modules/tripplebanner/views/img/{$file_name}" class="img-thumbnail"/>
                    {/if}
                {/if}
            </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#{$input.name}-selectbutton').click(function(e){
                $('#{$input.name}').trigger('click');
            });
            $('#{$input.name}').change(function(e){
                var val = $(this).val();
                var file = val.split(/[\\/]/);
                $('#{$input.name}-name').val(file[file.length-1]);
            });
        });
    </script>
    {/if}
    {if $input.name === 'TRIPPLEBANNER_LINK_1' ||  $input.name === 'TRIPPLEBANNER_LINK_2' ||  $input.name === 'TRIPPLEBANNER_LINK_3'}
        <div class="form-group">
            <div class="col-lg-6">
                <input id="{$input.name}" type="{$input.type}" name="{$input.name}" value="{Configuration::get($input.name)}" placeholder="Link"/>
            </div>
        </div>
    {/if}
   {/block}
   