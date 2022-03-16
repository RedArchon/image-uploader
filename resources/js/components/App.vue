<template>
    <div>
        <div class="mt-4">
            <file-pond
                name="image"
                ref="pond"
                label-idle="Click to choose image, or drag here...."
                @init="filepondInitialized"
                accepted-file-types="image/jpg, image/png, image/jpeg"
                @processfile="handleProcessedFile"
                max-file-size="1MB"
            />
        </div>
        <div class="mt-8 mb-24">
            <h2 class="text-2xl font-medium text-center">Image Gallery</h2>
            <div class="grid grid-cols-3 gap-2 justify-evenly mt-4">
                <div v-for="(image, index) in images" :key="index">
                    <img :src="'/storage/images/'+ image" alt="">
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import vueFilePond, {setOptions} from 'vue-filepond';
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

let serverMessage = {};
setOptions({
    server: {
        process: {
            url: './upload',
            onerror: (response) => {
              serverMessage = JSON.parse(response);
            },
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf_token"]').content
            }
        }
    },
    labelFileProcessingError: () => {
        return serverMessage.error;
    }
})

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);

export default {
    components: {
        FilePond
    },
    data() {
        return {
            images: null
        }
    },
    methods: {
        filepondInitialized() {
            console.log('Initialized Filepond Object:', this.$refs.pond);
        },
        handleProcessedFile(error, file) {
            if (error) {
                console.error(error)
                return;
            }

            this.images.unshift(file.serverId);
        }
    },

    mounted() {
        axios.get('/images')
            .then((response) => {
                this.images = response.data;
            })
            .catch((error) => {
                console.error(error);
            })
    }
}
</script>

<style scoped>

</style>
