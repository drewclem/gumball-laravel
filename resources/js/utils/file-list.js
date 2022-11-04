import { ref } from "vue";

export default function () {
    const files = ref([]);

    function addFiles(newFiles) {
        let newUploadableFiles = [...newFiles]
            .map((file) => new UploadableFile(file))
            .filter((file) => !fileExists(file.id))
            .filter((file) => validType(file.type));
        files.value = files.value.concat(newUploadableFiles);
    }

    function fileExists(otherId) {
        return files.value.some(({ id }) => id === otherId);
    }

    function validType(fileType) {
        const validTypes = [
            "image/jpeg",
            "image/png",
            "image/jpg",
            "image/heic",
        ];

        const isValid = validTypes.filter((type) => {
            return fileType.includes(type);
        });

        return isValid.length > 0;
    }

    function removeFile(index) {
        files.value.splice(index);
    }

    return { files, addFiles, removeFile };
}

class UploadableFile {
    constructor(file) {
        this.file = file;
        this.id = `${file.name}-${file.size}-${file.lastModified}-${file.type}`;
        this.url = URL.createObjectURL(file);
        this.name = file.name;
        this.type = file.type;
        this.status = null;
    }
}
