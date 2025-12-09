@props([
    'action',
    'method' => 'POST',
    'data' => [],
    'isEdit' => false,
    'jenis-aktivitas-id' => null,
    'existingFiles' => [],
])

<div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center">
    <div class="self-stretch border-gainsboro border-solid border-b-[1px] flex items-start py-5 px-6">
        <div class="flex flex-col items-start">
            <div class="relative leading-6 font-medium" x-text="'Form ' + (categories[kategori]?.label || '')"></div>
        </div>
    </div>
    
    <form
        method="POST"
        :action="action"
        autocomplete="off"
        enctype="multipart/form-data"
        class="self-stretch flex flex-col items-start p-6 gap-6 text-sm text-darkslategray-200"
        x-data="FormProgresData({
            action: '{{ $action }}',
            errors: {{ Js::from($errors->toArray() ?? []) }},
            existingFiles: {{ Js::from($existingFiles ?? []) }},
            baseFields: {{ Js::from(config('indicators.base_fields', [])) }},
            data: {{ Js::from($data) }}
        })"
        @submit="onSubmit()"
    >
        @csrf
        @if(strtoupper($method) !== 'POST')
            @method($method)
        @endif

        <input type="hidden" name="jenis_aktivitas_id" x-bind:value="jenisAktivitasId" />
        <input type="hidden" name="removed_files" x-bind:value="JSON.stringify(removedFiles)" />

        <div class="self-stretch flex flex-col gap-6">
            {{-- Date Fields --}}
            <template x-for="(field, key) in baseDateFields" :key="'date-' + key">
                <div class="self-stretch flex flex-col items-start gap-1.5">
                    <div class="self-stretch flex flex-col items-start gap-1.5">
                        <div class="relative leading-5 font-medium">
                            <span x-text="field.label"></span>
                            <template x-if="field.required">
                                <span class="text-red-500">*</span>
                            </template>
                        </div>
                        
                        <x-main.text-input 
                            type="date" 
                            x-bind:name="key" 
                            class="flex-1 leading-5 bg-transparent text-sm" 
                            x-bind:required="field.required"
                            x-bind:value="data[key] || ''"
                            x-on:invalid="$el.setCustomValidity(field.label + ' harus diisi')"
                            x-on:input="$el.setCustomValidity('')"
                        />
                        <div x-show="getErrorMessage(key)" class="text-tomato text-xs mt-1">
                            <p x-text="getErrorMessage(key)"></p>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Dynamic Fields --}}
            <template x-for="(field, key) in currentFields" :key="key">
                <div class="self-stretch flex flex-col items-start gap-1.5">
                    <div class="self-stretch flex flex-col items-start gap-1.5">
                        <div class="relative leading-5 font-medium">
                            <span x-text="field.label"></span>
                            <template x-if="field.satuan">
                                <span class="text-xs text-gray-400"> (<span x-text="field.satuan"></span>)</span>
                            </template>
                            <template x-if="field.required">
                                <span class="text-red-500">*</span>
                            </template>
                        </div>
                        
                        {{-- Select Field --}}
                        <template x-if="field.type === 'select'">
                            <select 
                                x-bind:name="key" 
                                class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                                x-bind:required="field.required"
                            >
                                <option value="">- Pilih -</option>
                                <template x-for="option in field.config.options || []" :key="option">
                                    <option 
                                        x-bind:value="option" 
                                        x-text="option"
                                        x-bind:selected="data[key] === option"
                                    ></option>
                                </template>
                            </select>
                        </template>
                        
                        {{-- Textarea Field --}}
                        <template x-if="field.type === 'textarea'">
                            <textarea 
                                x-bind:name="key" 
                                class="self-stretch rounded-lg bg-white border-lightgray border-solid border-[1px] py-3 px-4 text-darkgray focus:border-blue-500 focus:ring-blue-500 min-h-[120px] resize-y" 
                                x-bind:required="field.required"
                                x-bind:placeholder="'Enter ' + field.label.toLowerCase() + '...'"
                                x-text="data[key] || ''"
                            ></textarea>
                        </template>
                        
                        {{-- Date Field --}}
                        <template x-if="field.type === 'date'">
                            <x-main.text-input  
                                type="date" 
                                x-bind:name="key" 
                                class="self-stretch rounded-lg bg-white border-lightgray border-solid border-[1px] box-border py-2.5 px-4 text-darkgray focus:border-blue-500 focus:ring-blue-500" 
                                x-bind:required="field.required"
                                x-bind:value="data[key] || ''"
                            />
                        </template>
                        
                        {{-- Number Field --}}
                        <template x-if="field.type === 'number'">
                            <x-main.text-input 
                                type="number" 
                                step="0.01"
                                x-bind:name="key" 
                                class="flex-1 leading-5 bg-transparent text-sm"
                                x-bind:required="field.required"
                                x-bind:value="data[key] || ''"
                                x-on:invalid="$el.setCustomValidity(field.label + ' harus diisi')"
                                x-on:input="$el.setCustomValidity('')"
                            />
                        </template>
                        
                        {{-- Text Field --}}
                        <template x-if="field.type === 'text' || !field.type">
                            <x-main.text-input  
                                type="text" 
                                x-bind:name="key" 
                                class="flex-1 leading-5 bg-transparent text-sm" 
                                x-bind:required="field.required"
                                x-bind:value="data[key] || ''"
                            />
                        </template>

                        {{-- Error Display --}}
                        <div x-show="getErrorMessage(key)" class="text-tomato text-xs mt-1">
                            <p x-text="getErrorMessage(key)"></p>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Base Non-Date Fields --}}
            <template x-for="(field, key) in baseNonDateFields" :key="'base-' + key">
                <div class="self-stretch flex flex-col items-start gap-1.5">
                    <div class="self-stretch flex flex-col items-start gap-1.5">
                        <div class="relative leading-5 font-medium">
                            <span x-text="field.label"></span>
                            <template x-if="field.required">
                                <span class="text-red-500">*</span>
                            </template>
                        </div>
                        
                        {{-- File Upload Field --}}
                        <template x-if="field.type === 'file'">
                            <div class="w-full space-y-3">
                                <!-- File Input -->
                                <div class="relative">
                                    <input 
                                        type="file"
                                        x-bind:name="key + (field.multiple ? '[]' : '')"
                                        x-bind:required="field.required && !hasExistingFiles(key)"
                                        x-bind:multiple="field.multiple"
                                        x-bind:accept="field.accept || 'image/*'"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        @change="handleFileChange($event, key)"
                                    />

                                    <div class="flex items-stretch border border-gray-300 rounded-lg overflow-hidden bg-white hover:border-gray-400 transition-colors">
                                        <div class="flex-shrink-0 bg-gray-50 border-gray-300 border-solid border-r-[1px] px-4 py-2.5 cursor-pointer hover:bg-gray-100 transition-colors text-sm font-medium text-gray-700">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            Choose File
                                        </div>
                                        <div
                                            x-ref="'fileDisplay_' + key"
                                            class="flex-1 px-4 py-2.5 text-sm text-gray-700 bg-white truncate flex items-center"
                                            x-text="getFileDisplayText(key)"
                                        >
                                            No file chosen
                                        </div>
                                    </div>
                                </div>

                                <!-- Existing Files Preview -->
                                <template x-if="hasExistingFiles(key)">
                                    <div class="space-y-2">
                                        <div class="text-xs font-medium text-gray-600 mb-2">Current Files:</div>
                                        
                                        <template x-for="(file, idx) in getExistingFiles(key)" :key="'existing-' + idx">
                                            <div class="border rounded-lg p-3 flex items-center justify-between transition-all"
                                                 :class="isFileMarkedForRemoval(key, idx) ? 'bg-red-50 border-red-200 opacity-60' : 'bg-blue-50 border-blue-200'">
                                                <div class="flex items-center space-x-3 flex-1">
                                                    <!-- Image Preview -->
                                                    <template x-if="isImageFile(file.name || file.url)">
                                                        <div class="relative group">
                                                            <img 
                                                                :src="getFileUrl(file.url || file)" 
                                                                :alt="getFileName(file.name || file.url || file)"
                                                                class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-sm cursor-pointer group-hover:shadow-md transition-shadow"
                                                                @click="openFileModal(file, getFileName(file.name || file.url || file))"
                                                            />
                                                            <!-- Zoom indicator dengan pointer-events-none -->
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-75 transition-all pointer-events-none">
                                                                <svg class="w-4 h-4 text-white pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- File Info -->
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="getFileName(file.name || file.url || file)"></p>
                                                        <p class="text-xs text-gray-500" 
                                                           x-text="isFileMarkedForRemoval(key, idx) ? 'Marked for removal' : 'Current file'"></p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Action Buttons -->
                                                <div class="flex items-center space-x-2 ml-3">
                                                    <!-- View Button -->
                                                    <button type="button"
                                                            @click="openFileModal(file, getFileName(file.name || file.url || file))"
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        View
                                                    </button>
                                                    
                                                    <!-- Remove/Restore Button -->
                                                    <template x-if="!isFileMarkedForRemoval(key, idx)">
                                                        <button type="button" 
                                                                @click="removeExistingFile(key, idx)"
                                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Remove
                                                        </button>
                                                    </template>
                                                    
                                                    <template x-if="isFileMarkedForRemoval(key, idx)">
                                                        <button type="button" 
                                                                @click="restoreExistingFile(key, idx)"
                                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                            </svg>
                                                            Restore
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Hidden inputs for removed files -->
                                <template x-for="(removedFile, idx) in getRemovedFiles(key)" :key="'removed-' + idx">
                                    <input type="hidden" x-bind:name="'remove_' + key + '[]'" x-bind:value="removedFile" />
                                </template>

                                <!-- Error message -->
                                <div x-show="getErrorMessage(key)" class="text-tomato text-xs mt-1">
                                    <p x-text="getErrorMessage(key)"></p>
                                </div>
                            </div>
                        </template>
                        
                        {{-- Textarea Field --}}
                        <template x-if="field.type === 'textarea'">
                            <textarea 
                                x-bind:name="key" 
                                class="self-stretch border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-0 rounded-md px-3 py-2 resize-none leading-5 bg-transparent font-outfit text-sm min-h-[120px] resize-y" 
                                x-bind:required="field.required"
                                placeholder="Enter a catatan..."
                                x-text="data[key] || ''"
                            ></textarea>
                        </template>
                        
                        {{-- Other Input Types --}}
                        <template x-if="!['file', 'textarea', 'date'].includes(field.type)">
                            <x-main.text-input
                                x-bind:type="field.type" 
                                x-bind:name="key" 
                                class="flex-1 leading-5 bg-transparent text-sm" 
                                x-bind:required="field.required"
                                x-bind:value="data[key] || ''"
                            />
                        </template>

                        {{-- Error Display --}}
                        <div x-show="getErrorMessage(key)" class="text-tomato text-xs mt-1">
                            <p x-text="getErrorMessage(key)"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex gap-3 w-full justify-end">
            <button 
                type="button" 
                onclick="window.history.back()"
                class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline"
            >
                Cancel
            </button>
            <x-main.primary-button type="submit" class="!font-medium py-3 px-4 text-sm">
                {{ $isEdit ? 'Update' : 'Save' }}
            </x-main.primary-button>
        </div>
    </form>
</div>