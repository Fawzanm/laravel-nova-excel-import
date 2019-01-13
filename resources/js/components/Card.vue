<template>
    <card class="flex flex-col h-auto">
        <div class="px-3 py-3">
            <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-80 font-light">{{heading}}</h1>
            </div>
            <div class="py-6 px-8 w-full" >
            <input type="file"
            id="file"
            name="file"
            class="form-file-input"
            @change="save($event)">
            <label for="file" class="btn btn-default btn-primary">
            Choose file
            </label>
                <span class="text-gray-50">
                    {{label}}
                    </span>
            </div>

                <div class="flex">
                    <button
                        type="submit"
                        class="btn btn-default btn-primary ml-auto mt-auto"
                    @click="send"
                    >
                        <span>Import</span>
                    </button>
                </div>
        </div>
    </card>

</template>

<script>
    export default {
        props: [
            'card',
            'field',
            'resource',
            'resourceId',
            'resourceName',

        ],

        mounted() {
        this.required_fields=this.card.required_fields ? this.card.required_fields : '';
            this.model=this.card.model;
            this.heading=this.card.heading;
        },
        data() {
            return {
                file: '',
                required_fields:[],
                model:'',
                heading:'',
                label:'no file selected'
            }
        },
        methods: {
            save(event){
                let path = event.target.value;
                this.file=event.target.files[0];
                this.label = path.match(/[^\\/]*$/)[0];
            },
            send() {
                if (this.file!= '') {
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('required_fields', this.required_fields);
                    formData.append('model', this.model);
                    Nova.request().post('/nova-vendor/nova-excel-export/import',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(d => {
                        this.$toasted.show(d.data.message, {type: 'success'});
                    }).catch(d => {
                        if (this.required_fields != null) {
                            for (var key in d.response.data.error) {
                                this.$toasted.show(d.response.data.error[key][0], {type: 'error'})

                            }
                        }
                        this.$toasted.show(d.response.data.message, {type: 'error'})


                    });
                }
            }
        }
    }
</script>
