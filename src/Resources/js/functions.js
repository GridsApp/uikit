import Cropper from "cropperjs";
import Quill from "quill";
import collect from "collect.js";

export default class Functions {
    initEditor() {
        return {
            quill: false,
            value: "",
            init() {
                this.quill = new Quill(this.$el, {
                    theme: "snow",
                });

                //for edit
                this.$el.querySelector(".ql-editor").innerHTML =
                    this.$wire.value;

                this.quill.on("text-change", (delta, oldDelta, source) => {
                    this.value = this.$el.querySelector(".ql-editor").innerHTML;
                    this.$wire.value = this.value;
                });
            },
        };
    }
    initSelect(maxVisibleSelections, dispatchInit, dispatchChanged) {
        return {
            open: false,
            search: "",
            multiple: false,
            loading: false,

            selectedValues: null,
            selectedOptions: null,
            options: [],
            maxVisibleSelections: maxVisibleSelections,
            showMore: false,
            drawerOpen: false,

            init() {
                this.options = [];
                this.multiple = Array.isArray(this.$wire.value);

                this.selectedValues = this.multiple
                    ? [...(this.$wire.value || [])].map(String)
                    : this.$wire.value
                    ? String(this.$wire.value)
                    : null;

                this.selectedOptions = this.selectedOptions || [];

                this.isRemovingItem = false;

                this.$watch("selectedValues", (value) => {
                    if (this.isRemovingItem) return;

                    const optionsArray = collect([...this.options]).map(
                        (item) => {
                            item.value = String(item.value);
                            return item;
                        }
                    );

                    let values;

                    if (this.multiple) {
                        values = collect(value).map(String).toArray();
                        this.selectedOptions = optionsArray
                            .whereIn("value", values)
                            .toArray();

                        if (values.length === 0) {
                            this.open = false;
                        }
                    } else {
                        values = value ? String(value) : null;
                        this.selectedOptions = value
                            ? optionsArray.whereIn("value", [values]).toArray()
                            : [];
                        this.open = false;
                    }

                    this.$wire.value = values;

                    if (this.dispatchChanged) {
                        this.$dispatch(this.dispatchChanged, {
                            selected: values,
                        });
                    }
                });

                if (dispatchInit != "") {
                    setTimeout(() => {
                        this.$dispatch(dispatchInit, {
                            selected: this.selectedValues,
                        });
                    }, 0);
                }

                if (
                    (!this.multiple && this.selectedValues != null) ||
                    (this.multiple && this.selectedValues.length > 0)
                ) {
                    this.getSelectedOptions();
                }
            },

            get visibleOptions() {
                if (this.multiple) {
                    if (this.showMore) {
                        return this.selectedOptions;
                    }
                    return this.selectedOptions.slice(
                        0,
                        this.maxVisibleSelections
                    );
                } else {
                    console.log(this.selectedOptions);
                    return this.selectedOptions;
                }
            },

            get hiddenOptions() {
                return this.multiple
                    ? Array.isArray(this.selectedOptions) &&
                      this.selectedOptions.length > 0
                        ? this.selectedOptions.slice(this.maxVisibleSelections)
                        : []
                    : this.maxVisibleSelections;
            },

            handleCreateCallback(event) {
                if (this.multiple) {
                    this.selectedValues.push(event.detail.id);
                    this.$wire.value.push(event.detail.id);
                } else {
                    this.selectedValues = [event.detail.id];
                    this.$wire.value = event.detail.id;
                }

                if (dispatchChanged != "") {
                    this.$dispatch(dispatchChanged, {
                        selected: this.$wire.value,
                    });
                }

                this.getSelectedOptions();
            },

            showMoreHandler(event) {
                event.stopPropagation();
                this.showMore = !this.showMore;
            },

            async searchHandler() {
                this.getOptions();
            },

            async getSelectedOptions() {
                let response = await this.$wire.getOptions(
                    null,
                    this.selectedValues
                );

                this.selectedOptions = [...response.original];

                this.drawerOpen = false;
            },

            async getOptions() {
                this.loading = true;
                let response = await this.$wire.getOptions(this.search);

                this.options = [...response.original];

                this.loading = false;
            },

            async openOptions() {
                if (this.open) {
                    this.open = false;
                } else {
                    this.open = true;
                    if (this.options.length == 0) {
                        this.getOptions();
                    }
                }
            },

            openQuickAdd(event) {
                event.stopPropagation();
                this.open = false;
                this.drawerOpen = !this.drawerOpen;
            },

            async handleClear(event) {
                event.stopPropagation();
                this.search = "";
                this.getOptions();

                this.selectedOptions = this.multiple ? [] : null;
                this.selectedValues = this.multiple ? [] : null;
                this.$wire.value = this.multiple ? [] : null;

                if (dispatchChanged != "") {
                    this.$dispatch(dispatchChanged, {
                        selected: null,
                    });
                }
            },

            handleClearSelection(event, optionIdentifier) {
                event.stopPropagation();
                event.preventDefault();

                if (this.multiple) {
                    this.isRemovingItem = true;

                    try {
                        const currentValues = [...this.selectedValues].map(
                            String
                        );
                        const currentOptions = [...this.selectedOptions];

                        const optionToRemove = currentOptions.find(
                            (opt) =>
                                opt.identifier.trim().toLowerCase() ===
                                optionIdentifier.trim().toLowerCase()
                        );

                        if (optionToRemove) {
                            const valueToRemove = String(optionToRemove.value);

                            const newValues = currentValues.filter(
                                (v) => v !== valueToRemove
                            );
                            const newOptions = currentOptions.filter(
                                (o) => String(o.value) !== valueToRemove
                            );

                            this.selectedValues = newValues;
                            this.selectedOptions = newOptions;
                            this.$wire.value = newValues;

                         
                        }
                    } catch (error) {
                        console.error("Error removing item:", error);
                    } finally {
                        setTimeout(() => {
                            this.isRemovingItem = false;
                        }, 100);
                    }
                }
            },
        };
    }

    // initSelect(maxVisibleSelections, dispatchInit, dispatchChanged) {
    //     return {
    //         open: false,
    //         search: "",
    //         multiple: false,
    //         loading: false,

    //         selectedValues: null,
    //         selectedOptions: null,
    //         options: [],
    //         maxVisibleSelections: maxVisibleSelections,
    //         showMore: false,
    //         drawerOpen: false,


    
    //         init() {
    //             this.options = [];
    //             this.multiple = Array.isArray(this.$wire.value);

    //             this.selectedValues = this.$wire.value;
    //             this.selectedOptions = this.selectedOptions || [];

    //             this.$watch("selectedValues", (value) => {
    //                 let values;

    //                 const optionsArray = collect([...this.options]).map(
    //                     (item) => {
    //                         item.value = String(item.value);
    //                         return item;
    //                     }
    //                 );

    //                 if (this.multiple) {
    //                     values = collect(value)
    //                         .map((item) => String(item))
    //                         .toArray();
    //                     this.selectedOptions = optionsArray
    //                         .whereIn("value", values)
    //                         .toArray();

    //                     if (this.selectedValues.length == 0) {
    //                         this.open = false;
    //                     }
    //                 } else {
    //                     values = String(value);
    //                     this.selectedOptions = optionsArray
    //                         .whereIn("value", [values])
    //                         .toArray();
    //                     this.open = false;
    //                 }

    //                 this.$wire.value = values;

    //                 if (dispatchChanged != "") {
    //                     this.$dispatch(dispatchChanged, { selected: values });
    //                 }
    //             });

    //             if (dispatchInit != "") {
    //                 setTimeout(() => {
    //                     this.$dispatch(dispatchInit, {
    //                         selected: this.selectedValues,
    //                     });
    //                 }, 0);
    //             }

    //             if (
    //                 (!this.multiple && this.selectedValues != null) ||
    //                 (this.multiple && this.selectedValues.length > 0)
    //             ) {
    //                 this.getSelectedOptions();
    //             }
    //         },

    //         get visibleOptions() {
    //             if (this.multiple) {
    //                 if (this.showMore) {
    //                     return this.selectedOptions;
    //                 }
    //                 return this.selectedOptions.slice(
    //                     0,
    //                     this.maxVisibleSelections
    //                 );
    //             } else {
    //                 console.log(this.selectedOptions);
    //                 return this.selectedOptions;
    //             }
    //         },

    //         get hiddenOptions() {
    //             return this.multiple
    //                 ? Array.isArray(this.selectedOptions) &&
    //                   this.selectedOptions.length > 0
    //                     ? this.selectedOptions.slice(this.maxVisibleSelections)
    //                     : []
    //                 : this.maxVisibleSelections;
    //         },

    //         handleCreateCallback(event) {
    //             if (this.multiple) {
    //                 this.selectedValues.push(event.detail.id);
    //                 this.$wire.value.push(event.detail.id);
    //             } else {
    //                 this.selectedValues = [event.detail.id];
    //                 this.$wire.value = event.detail.id;
    //             }

    //             if (dispatchChanged != "") {
    //                 this.$dispatch(dispatchChanged, {
    //                     selected: this.$wire.value,
    //                 });
    //             }

    //             this.getSelectedOptions();
    //         },

    //         showMoreHandler(event) {
    //             event.stopPropagation();
    //             this.showMore = !this.showMore;
    //         },

    //         async searchHandler() {
    //             this.getOptions();
    //         },

    //         async getSelectedOptions() {
    //             let response = await this.$wire.getOptions(
    //                 null,
    //                 this.selectedValues
    //             );

    //             this.selectedOptions = [...response.original];

    //             this.drawerOpen = false;
    //         },

    //         async getOptions() {
    //             this.loading = true;
    //             let response = await this.$wire.getOptions(this.search);

    //             this.options = [...response.original];

    //             this.loading = false;
    //         },

    //         async openOptions() {
    //             if (this.open) {
    //                 this.open = false;
    //             } else {
    //                 this.open = true;
    //                 if (this.options.length == 0) {
    //                     this.getOptions();
    //                 }
    //             }
    //         },

    //         openQuickAdd(event) {
    //             event.stopPropagation();
    //             this.open = false;
    //             this.drawerOpen = !this.drawerOpen;
    //         },

    //         async handleClear(event) {
    //             event.stopPropagation();
    //             this.search = "";
    //             this.getOptions();

    //             this.selectedOptions = this.multiple ? [] : null;
    //             this.selectedValues = this.multiple ? [] : null;
    //             this.$wire.value = this.multiple ? [] : null;

    //             if (dispatchChanged != "") {
    //                 this.$dispatch(dispatchChanged, {
    //                     selected: null,
    //                 });
    //             }
    //         },

    //         handleClearSelection(event, optionLabel) {
    //             event.stopPropagation();
    //             if (this.selectedOptions) {
    //                 this.selectedOptions = this.selectedOptions.filter(
    //                     (option) => option.label !== optionLabel
    //                 );

    //                 if (this.multiple) {

    //                     this.selectedValues = this.selectedOptions.map(
    //                         (option) => option.value
    //                     );

    //                     this.$wire.value = this.selectedValues;

    //                     if (this.selectedValues.length == 0) {
    //                         this.open = false;
    //                     }
    //                 } else {
    //                     this.$wire.value = null;
    //                 }
    //             }
    //         },
    //     };
    // }

    updateLabel(value) {
        document.getElementById("label-field").value = `Theater ${value}`;
    }
    initCarousel(carousel) {
        var $el = carousel;

        if ($el == null) {
            return;
        }

        let isDown = false;
        let startX;
        let scrollLeft;

        $el.addEventListener("mousedown", (e) => {
            isDown = true;
            startX = e.pageX - $el.offsetLeft;
            scrollLeft = $el.scrollLeft;
        });

        $el.addEventListener("mouseleave", () => {
            isDown = false;
        });

        $el.addEventListener("mouseup", () => {
            isDown = false;
        });

        $el.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - $el.offsetLeft;
            const walk = (x - startX) * 1;
            $el.scrollLeft = scrollLeft - walk;
        });
    }

    fileUploader(aspectRatio, multiple) {
        return {
            show: false,
            uploading: false,
            // progress: 0,
            previews: [],
            previewSelectedIndex: 0,

            progress: {},

            showPreview: false,
            cropper: null,
            enable_crop: false,
            uploaded: [],
            uploadedIndex: 0,

            showImageModal: false,
            modalImageUrl: "",

            showImagePreview(imageUrl) {
                this.modalImageUrl = imageUrl;
                this.showImageModal = true;
            },

            // Function to close the modal
            closeModal() {
                this.showImageModal = false;
                this.modalImageUrl = [];
            },

            removeImage(index) {
                this.uploaded.splice(index, 1);

                if (this.uploaded.length == 0) {
                    this.show = false;
                }
            },

            cancelCropping() {
                this.cropper.destroy();
                this.cropper = null;
            },

            doneCropping() {
                const croppedCanvas = this.cropper.getCroppedCanvas();
                // const croppedImage = croppedCanvas.toDataURL();

                this.previews[this.previewSelectedIndex] = {
                    ...this.previews[this.previewSelectedIndex],
                    cropping: this.cropper.getData(),
                    url: croppedCanvas.toDataURL(),
                };

                this.cropper.destroy();
                this.cropper = null;
            },

            initCrop() {
                this.previews[this.previewSelectedIndex].url =
                    this.previews[this.previewSelectedIndex].original_url;

                const image = this.$refs.previewimage;

                image.src =
                    this.previews[this.previewSelectedIndex].original_url;

                this.cropper = new Cropper(image, {
                    aspectRatio: aspectRatio,
                });
            },

            initCarousel() {
                window.Functions.initCarousel(this.$refs.carouseldiv);
            },

            init() {
                var value = this.$wire.value;

                this.uploaded = [];

                if (Array.isArray(value)) {
                    this.uploaded = value;
                } else {
                    if (value != null) {
                        this.uploaded = [value];
                    } else {
                        this.uploaded = [];
                    }
                }

                this.initCarousel();
            },

            async uploadFile(preview, index, wire) {
                var newpreview;

                await wire.upload(
                    "file." + index,
                    preview.file,
                    (uploadedFilename) => {
                        console.log("Success");

                        newpreview = {
                            ...preview,
                            uploaded: uploadedFilename,
                            status: "uploaded",
                            progress: 101,
                        };

                        this.previews[index] = newpreview;

                        if (multiple) {
                            this.uploaded.push(newpreview);
                            this.$wire.value.push(newpreview);
                        } else {
                            this.uploaded = [];
                            this.uploaded.push(newpreview);
                            this.$wire.value = newpreview;
                        }
                    },
                    () => {
                        console.log("Error");

                        newpreview = {
                            ...preview,
                            uploaded: null,
                            status: "failed",
                            progress: 100,
                        };

                        if (multiple) {
                            this.previews[index] = newpreview;
                        } else {
                            this.previews = [];
                            this.previews.push(newpreview);
                        }

                        // Error callback...
                    },
                    (event) => {
                        console.log("Progress " + event.detail.progress);

                        newpreview = {
                            ...preview,
                            progress: event.detail.progress,
                        };

                        if (multiple) {
                            this.previews[index] = newpreview;
                        } else {
                            this.previews = [];
                            this.previews.push(newpreview);
                        }
                    },
                    () => {
                        console.log("Cancelled");

                        newpreview = {
                            ...preview,
                            uploaded: null,
                            status: "cancelled",
                            progress: 100,
                        };

                        if (multiple) {
                            this.previews[index] = newpreview;
                        } else {
                            this.previews = [];
                            this.previews.push(newpreview);
                        }

                        setTimeout(() => {
                            newpreview = { ...newpreview, progress: 103 };
                            this.previews[index] = newpreview;
                        }, 1000);
                    }
                );

                // await wire.upload(
                //     "file",
                //     preview.file,
                //     async (uploadedFilename) => {

                //         console.log("Completed");
                //         // newpreview = {
                //         //     ...preview,
                //         //     uploaded: uploadedFilename,
                //         //     status: "uploaded",
                //         //     progress: 101,
                //         // };

                //         // this.previews[index] = newpreview;

                //         // if (multiple) {
                //         //     this.uploaded.push(newpreview);
                //         //     this.$wire.value.push(newpreview);
                //         // } else {
                //         //     this.uploaded = [];
                //         //     this.uploaded.push(newpreview);
                //         //     this.$wire.value = newpreview;
                //         // }
                //     },

                //     async () => {

                //         console.log("Completed 1");

                //         // newpreview = {
                //         //     ...preview,
                //         //     uploaded: null,
                //         //     status: "failed",
                //         //     progress: 100,
                //         // };

                //         // if (multiple) {
                //         //     this.previews[index] = newpreview;
                //         // } else {
                //         //     this.previews = [];
                //         //     this.previews.push(newpreview);
                //         // }
                //     },
                //     async (event) => {

                //         console.log(event);
                //         console.log("error");
                //         // newpreview = {
                //         //     ...preview,
                //         //     progress: event.detail.progress,
                //         // };

                //         // if (multiple) {
                //         //     this.previews[index] = newpreview;
                //         // } else {
                //         //     this.previews = [];
                //         //     this.previews.push(newpreview);
                //         // }
                //     },
                //     async () => {
                //         console.log("cancelled");
                //         // newpreview = {
                //         //     ...preview,
                //         //     uploaded: null,
                //         //     status: "cancelled",
                //         //     progress: 100,
                //         // };
                //         // this.previews.splice(index, 1, newpreview);
                //     }
                // );
            },

            //When i click upload from inside the Preview Modal
            upload() {
                this.showPreview = false;
                this.show = true;

                this.previews.forEach(async (preview, index) => {
                    this.uploadFile(preview, index, this.$wire);
                });

                const interval = setInterval(() => {
                    if (
                        collect(this.previews)
                            .where("progress", "<", 100)
                            .count() == 0
                    ) {
                        clearInterval(interval);
                        this.show = false;
                    }
                }, 3000);
            },

            handleImageChange(index) {
                if (this.cropper != null) {
                    this.cropper.destroy();
                    this.cropper = null;
                }

                if (this.previewSelectedIndex == index) {
                    //delete image
                    this.previews.splice(this.previewSelectedIndex, 1);

                    if (this.previews.length == 0) {
                        this.showPreview = false;
                        return;
                    }

                    if (this.previewSelectedIndex > 0) {
                        this.previewSelectedIndex = this.previewSelectedIndex;
                    } else {
                        this.previewSelectedIndex = 0;
                    }

                    return;
                }

                this.previewSelectedIndex = index;

                this.enable_crop =
                    this.previews[this.previewSelectedIndex].enable_crop;
            },

            closePreview() {
                this.showPreview = false;
                this.previews = [];
            },

            formatBytes(bytes, seperator = "") {
                const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
                if (bytes == 0) return "n/a";
                const i = parseInt(
                    Math.floor(Math.log(bytes) / Math.log(1024)),
                    10
                );
                if (i === 0) return `${bytes}${seperator}${sizes[i]}`;
                return `${(bytes / 1024 ** i).toFixed(1)}${seperator}${
                    sizes[i]
                }`;
            },

            handleFileUpload(event) {
                var files = [];

                this.previews = [];

                [...event.target.files].forEach((image, index) => {
                    files.push({
                        url: URL.createObjectURL(image),
                        file: image,
                        name: image.name,
                        enable_crop:
                            ["jpeg", "JPEG", "png", "PNG"].includes(
                                image.type.split("/")[1] ?? null
                            ) ?? false,
                        size: this.formatBytes(image.size),
                        progress: 0,
                        original_url: URL.createObjectURL(image),
                        cancleHandler: () => {
                            return this.$wire.cancelUpload("file." + index);
                        },
                    });
                });

                this.previews = [...files];
                this.preview = this.previews[0] ?? null;

                this.showPreview = true;

                //Must be changed
                this.enable_crop = this.previews[0].enable_crop;

                event.target.value = "";
            },

            // showPreviewHandler() {
            //     this.show = false;
            // },
        };
    }
    initTranslatable() {
        return {
            active: 0,
            focusedElement: null,

            keyDown(event) {
                this.focusedElement = event.target.closest(".language-element");

                let langPicker =
                    this.focusedElement?.querySelector(".lang-picker");

                let values = [];
                langPicker.querySelectorAll("option").forEach((option) => {
                    values.push(option.value);
                });

                let lastIndex = values.length - 1;

                if (event.key === "ArrowUp" || event.keyCode === 38) {
                    if (parseInt(langPicker.value) == lastIndex) {
                        langPicker.value = "0";
                    } else {
                        langPicker.value = String(
                            parseInt(langPicker.value) + 1
                        );
                    }

                    this.active = langPicker.value;

                    this.updateFocusedElement();
                } else if (event.key === "ArrowDown" || event.keyCode === 40) {
                    if (parseInt(langPicker.value) === 0) {
                        langPicker.value = String(lastIndex);
                    } else {
                        langPicker.value = String(
                            parseInt(langPicker.value) - 1
                        );
                    }
                    this.active = langPicker.value;

                    this.updateFocusedElement();
                }
            },

            updateFocusedElement() {
                let formInput = this.focusedElement
                    .querySelector(".toggle-active-" + this.active)
                    .querySelector(".twa-form-input,[contenteditable=true]");

                let interval = setInterval(() => {
                    let toggleArea = this.focusedElement.querySelector(
                        ".toggle-active-" + this.active
                    );

                    if (
                        toggleArea != null &&
                        toggleArea?.style.display != "none"
                    ) {
                        clearInterval(interval);

                        if (formInput) {
                            formInput.focus();
                        }
                    }
                }, 100);
            },

            handleChanged(event) {
                this.active = event.detail.languageIndex;
                this.updateFocusedElement();
            },
        };
    }

    initTextField(channel) {
        return {
            channel: channel,
            slug: "",
            wire: null,

            init() {
                this.wire = () => {
                    return this.$wire;
                };
            },

            handleSlug(event) {
                let slug = event.detail.value
                    .toLowerCase()
                    .replace(/\s+/g, "-");
                this.wire().value = slug;
            },

            handleInput(event) {
                window.dispatchEvent(
                    new CustomEvent(this.channel, {
                        detail: {
                            value: event.target.value,
                        },
                    })
                );
            },
        };
    }

    arraysEqualUnordered(arr1, arr2) {
        // Check if arrays have the same length
        if (arr1.length !== arr2.length) {
            return false;
        }

        arr1 = arr1.map((element) => String(element));
        arr2 = arr2.map((element) => String(element));

        // Sort both arrays and compare each element
        let sortedArr1 = arr1.slice().sort(); // slice() to avoid mutating the original arrays
        let sortedArr2 = arr2.slice().sort();

        for (let i = 0; i < sortedArr1.length; i++) {
            if (sortedArr1[i] !== sortedArr2[i]) {
                return false;
            }
        }

        return true; // Arrays are equal (regardless of order)
    }
    initTable() {
        return {
            selected: [],
            selectable: [],
            values: [],
            coordinates: {},
            selectedAll: false,
            actionsActive: null,
            openOptionsDropdown: false,
            allActions: [],
            actions: {
                allowEdit: true,
                allowDelete: true,
            },

            init() {
                let selectable = [];
                document
                    .querySelectorAll(".checkbox-row:not(:disabled)")
                    .forEach((checkbox) => {
                        selectable.push(String(checkbox.value));
                    });

                this.selectable = selectable;

                let actions = [];

                let defaultActions = {
                    allowEdit: true,
                    allowDelete: true,
                };

                document.querySelectorAll(".td-actions").forEach((tdAction) => {
                    if (tdAction.getAttribute("data-target")) {
                        actions.push({
                            row: tdAction.getAttribute("data-target"),
                            actions: {
                                ...defaultActions,
                                allowEdit:
                                    tdAction.getAttribute(
                                        "data-operation-disable-edit"
                                    ) != "true",
                                allowDelete:
                                    tdAction.getAttribute(
                                        "data-operation-disable-delete"
                                    ) != "true",
                            },
                        });
                    }
                });

                this.allActions = actions;
            },

            async handleDelete() {
                var response = await this.$wire.handleDelete([
                    this.actionsActive,
                ]);
                this.refresh();
                this.fillValues();
            },

            async handleDeleteAll() {
                var response = await this.$wire.handleDelete(this.selected);

                this.refresh();
                this.fillValues();
            },

            fillValues() {
                if (this.values.length > 0) {
                    return;
                }

                this.values = [];

                let checkboxes = document.querySelectorAll(
                    "input[type=checkbox].checkbox-row"
                );

                var values = [];
                checkboxes.forEach(function (checkbox) {
                    values.push(checkbox.value);
                });
                this.values = values;
            },

            refresh() {
                this.selectedAll = false;
                this.selected = [];
                this.values = [];
                this.actionsActive = null;
            },

            handleSelect() {
                this.fillValues();

                this.selectedAll = window.Functions.arraysEqualUnordered(
                    this.selected,
                    this.selectable
                );
            },

            handleSelectAll(event) {
                this.fillValues();

                if (
                    window.Functions.arraysEqualUnordered(
                        this.selected,
                        this.selectable
                    )
                ) {
                    this.selected = [];
                    return;
                }

                this.selected = collect([
                    ...this.selected,
                    ...this.selectable,
                ]).toArray();
            },

            handleClickAway() {
                this.actionsActive = null;

                this.$event.stopPropagation();
            },

            checkTDActionsDisabled(id) {
                let obj = collect(this.allActions).where("row", id).first();

                if (obj == null) {
                    return true;
                }

                let actions = obj["actions"];

                return Object.values(actions).every((value) => value == false);
            },

            handleBox(event, id) {
                console.log("here");
                var tdAction = document.getElementById("td-actions-" + id);

                if (this.checkTDActionsDisabled(id)) {
                    return false;
                }

                this.actions = collect(this.allActions)
                    .where("row", id)
                    .first()["actions"];

                // if(tdAction.getAttribute('data-operation-disable-edit') == "true"){
                //     this.actions = { ...this.actions , allowEdit : false};
                // }else{
                //     this.actions = { ...this.actions , allowEdit : true};
                // }

                // if(tdAction.getAttribute('data-operation-disable-delete') == "true"){
                //     this.actions = { ...this.actions , allowDelete : false};
                // }else{
                //     this.actions = { ...this.actions , allowDelete : true};
                // }

                const rect = tdAction.getBoundingClientRect();

                const pageX =
                    window.innerWidth - rect.right + 0.75 * rect.width;

                const pageY = rect.top + window.pageYOffset + 0.5 * rect.height;

                let dataId = tdAction.parentElement.getAttribute("data-id");

                this.coordinates[dataId] = { x: pageX, y: pageY };

                if (this.actionsActive == id) {
                    this.actionsActive = null;
                } else {
                    setTimeout(() => {
                        var old = 0;
                        if (this.actionsActive != null) {
                            old = this.coordinates[this.actionsActive].y;
                        }

                        this.actionsActive = parseInt(id);
                    }, 0);
                }
            },

            openDropdownOptions() {
                this.openOptionsDropdown = !this.openOptionsDropdown;
            },
        };
    }

    initFilter() {
        return {
            open: false,
            hideField2: false,

            openDropdown() {
                this.open = true;
            },

            initFilterItem(initSelected) {
                return {
                    openDropdown: initSelected == "true" || initSelected == 1,
                    hideField2: false,

                    optionChanged(event) {
                        this.hideField2 = event.target.value === "between";
                    },
                };
            },
        };
    }

    TWAToast(title, description, type, position) {
        window.dispatchEvent(
            new CustomEvent("toast-show", {
                detail: {
                    type: type,
                    message: title,
                    description: description,
                    position: position,
                    html: "",
                },
            })
        );
    }

    initToast() {
        return {
            toasts: [],
            toastsHovered: false,
            expanded: false,
            layout: "default",
            position: "top-center",
            paddingBetweenToasts: 15,
            init() {
                // console.log("here")
                if (this.position.includes("bottom")) {
                    this.$el.firstElementChild.classList.add("toast-bottom");
                    this.$el.firstElementChild.classList.add(
                        "opacity-0",
                        "translate-y-full"
                    );
                } else {
                    this.$el.firstElementChild.classList.add(
                        "opacity-0",
                        "-translate-y-full"
                    );
                }

                setTimeout(() => {
                    setTimeout(() => {
                        if (this.position.includes("bottom")) {
                            this.$el.firstElementChild.classList.remove(
                                "opacity-0",
                                "translate-y-full"
                            );
                        } else {
                            this.$el.firstElementChild.classList.remove(
                                "opacity-0",
                                "-translate-y-full"
                            );
                        }
                        this.$el.firstElementChild.classList.add(
                            "opacity-100",
                            "translate-y-0"
                        );

                        setTimeout(() => {
                            this.stackToasts();
                        }, 10);
                    }, 5);
                }, 50);

                setTimeout(() => {
                    setTimeout(() => {
                        this.$el.firstElementChild.classList.remove(
                            "opacity-100"
                        );
                        this.$el.firstElementChild.classList.add("opacity-0");

                        if (this.toasts.length === 1) {
                            this.$el.firstElementChild.classList.remove(
                                "translate-y-0"
                            );
                            this.$el.firstElementChild.classList.add(
                                "-translate-y-full"
                            );
                        }

                        // Remove the toast
                        // setTimeout(() => {
                        //     this.deleteToastWithId(toast?.id);
                        // }, 300);
                    }, 5);
                }, 4000);

                const layout = this.layout;
                this.$watch("toastsHovered", (value) => {
                    if (layout == "default") {
                        if (this.position.includes("bottom")) {
                            this.resetBottom();
                        } else {
                            this.resetTop();
                        }
                        if (value) {
                            // calculate the new positions
                            this.expanded = true;
                            if (layout == "default") {
                                this.stackToasts();
                            }
                        } else {
                            if (layout == "default") {
                                this.expanded = false;

                                this.stackToasts();

                                setTimeout(() => {
                                    this.stackToasts();
                                }, 10);
                            }
                        }
                    }
                });
            },
            deleteToastWithId(id) {
                for (let i = 0; i < this.toasts.length; i++) {
                    if (this.toasts[i].id === id) {
                        this.toasts.splice(i, 1);
                        break;
                    }
                }
            },
            burnToast(id) {
                const burnToast = this.getToastWithId(id);
                const burnToastElement = document.getElementById(burnToast.id);
                if (burnToastElement) {
                    if (this.toasts.length == 1) {
                        if (this.layout == "default") {
                            this.expanded = false;
                        }
                        burnToastElement.classList.remove("translate-y-0");
                        if (this.position.includes("bottom")) {
                            burnToastElement.classList.add("translate-y-full");
                        } else {
                            burnToastElement.classList.add("-translate-y-full");
                        }
                        burnToastElement.classList.add("-translate-y-full");
                    }
                    burnToastElement.classList.add("opacity-0");

                    setTimeout(() => {
                        this.deleteToastWithId(id);
                        setTimeout(() => {
                            this.stackToasts();
                        }, 1);
                    }, 300);
                }
            },
            getToastWithId(id) {
                for (let i = 0; i < this.toasts.length; i++) {
                    if (this.toasts[i].id === id) {
                        return this.toasts[i];
                    }
                }
            },
            stackToasts() {
                this.positionToasts();
                this.calculateHeightOfToastsContainer();
                let that = this;
                setTimeout(function () {
                    that.calculateHeightOfToastsContainer();
                }, 300);
            },
            positionToasts() {
                if (this.toasts.length == 0) return;
                let topToast = document.getElementById(this.toasts[0].id);
                topToast.style.zIndex = 100;
                if (this.expanded) {
                    if (this.position.includes("bottom")) {
                        topToast.style.top = "auto";
                        topToast.style.bottom = "0px";
                    } else {
                        topToast.style.top = "0px";
                    }
                }
                let bottomPositionOfFirstToast =
                    this.getBottomPositionOfElement(topToast);
                if (this.toasts.length == 1) return;
                let middleToast = document.getElementById(this.toasts[1].id);
                middleToast.style.zIndex = 90;
                if (this.expanded) {
                    let middleToastPosition =
                        topToast.getBoundingClientRect().height +
                        this.paddingBetweenToasts +
                        "px";
                    if (this.position.includes("bottom")) {
                        middleToast.style.top = "auto";
                        middleToast.style.bottom = middleToastPosition;
                    } else {
                        middleToast.style.top = middleToastPosition;
                    }
                    middleToast.style.scale = "100%";
                    middleToast.style.transform = "translateY(0px)";
                } else {
                    middleToast.style.scale = "94%";
                    if (this.position.includes("bottom")) {
                        middleToast.style.transform = "translateY(-16px)";
                    } else {
                        this.alignBottom(topToast, middleToast);
                        middleToast.style.transform = "translateY(16px)";
                    }
                }
                if (this.toasts.length == 2) return;
                let bottomToast = document.getElementById(this.toasts[2].id);
                bottomToast.style.zIndex = 80;
                if (this.expanded) {
                    let bottomToastPosition =
                        topToast.getBoundingClientRect().height +
                        this.paddingBetweenToasts +
                        middleToast.getBoundingClientRect().height +
                        this.paddingBetweenToasts +
                        "px";
                    if (this.position.includes("bottom")) {
                        bottomToast.style.top = "auto";
                        bottomToast.style.bottom = bottomToastPosition;
                    } else {
                        bottomToast.style.top = bottomToastPosition;
                    }
                    bottomToast.style.scale = "100%";
                    bottomToast.style.transform = "translateY(0px)";
                } else {
                    bottomToast.style.scale = "88%";
                    if (this.position.includes("bottom")) {
                        bottomToast.style.transform = "translateY(-32px)";
                    } else {
                        this.alignBottom(topToast, bottomToast);
                        bottomToast.style.transform = "translateY(32px)";
                    }
                }
                if (this.toasts.length == 3) return;
                let burnToast = document.getElementById(this.toasts[3].id);
                burnToast.style.zIndex = 70;
                if (this.expanded) {
                    let burnToastPosition =
                        topToast.getBoundingClientRect().height +
                        this.paddingBetweenToasts +
                        middleToast.getBoundingClientRect().height +
                        this.paddingBetweenToasts +
                        bottomToast.getBoundingClientRect().height +
                        this.paddingBetweenToasts +
                        "px";
                    if (this.position.includes("bottom")) {
                        burnToast.style.top = "auto";
                        burnToast.style.bottom = burnToastPosition;
                    } else {
                        burnToast.style.top = burnToastPosition;
                    }
                    burnToast.style.scale = "100%";
                    burnToast.style.transform = "translateY(0px)";
                } else {
                    burnToast.style.scale = "82%";
                    this.alignBottom(topToast, burnToast);
                    burnToast.style.transform = "translateY(48px)";
                }
                burnToast.firstElementChild.classList.remove("opacity-100");
                burnToast.firstElementChild.classList.add("opacity-0");
                let that = this;
                // Burn 🔥 (remove) last toast
                setTimeout(function () {
                    that.toasts.pop();
                }, 300);
                if (this.position.includes("bottom")) {
                    middleToast.style.top = "auto";
                }
                return;
            },
            alignBottom(element1, element2) {
                // Get the top position and height of the first element
                let top1 = element1.offsetTop;
                let height1 = element1.offsetHeight;
                // Get the height of the second element
                let height2 = element2.offsetHeight;
                // Calculate the top position for the second element
                let top2 = top1 + (height1 - height2);
                // Apply the calculated top position to the second element
                element2.style.top = top2 + "px";
            },
            alignTop(element1, element2) {
                // Get the top position of the first element
                let top1 = element1.offsetTop;
                // Apply the same top position to the second element
                element2.style.top = top1 + "px";
            },
            resetBottom() {
                for (let i = 0; i < this.toasts.length; i++) {
                    if (document.getElementById(this.toasts[i].id)) {
                        let toastElement = document.getElementById(
                            this.toasts[i].id
                        );
                        toastElement.style.bottom = "0px";
                    }
                }
            },
            resetTop() {
                for (let i = 0; i < this.toasts.length; i++) {
                    if (document.getElementById(this.toasts[i].id)) {
                        let toastElement = document.getElementById(
                            this.toasts[i].id
                        );
                        toastElement.style.top = "0px";
                    }
                }
            },
            getBottomPositionOfElement(el) {
                return (
                    el.getBoundingClientRect().height +
                    el.getBoundingClientRect().top
                );
            },
            calculateHeightOfToastsContainer() {
                if (this.toasts.length == 0) {
                    this.$el.style.height = "0px";
                    return;
                }
                const lastToast = this.toasts[this.toasts.length - 1];
                const lastToastRectangle = document
                    .getElementById(lastToast.id)
                    .getBoundingClientRect();
                const firstToast = this.toasts[0];
                const firstToastRectangle = document
                    .getElementById(firstToast.id)
                    .getBoundingClientRect();
                if (this.toastsHovered) {
                    if (this.position.includes("bottom")) {
                        this.$el.style.height =
                            firstToastRectangle.top +
                            firstToastRectangle.height -
                            lastToastRectangle.top +
                            "px";
                    } else {
                        this.$el.style.height =
                            lastToastRectangle.top +
                            lastToastRectangle.height -
                            firstToastRectangle.top +
                            "px";
                    }
                } else {
                    this.$el.style.height = firstToastRectangle.height + "px";
                }
            },

            toastLayout(event) {
                this.layout = event.detail.layout; // Use this.layout
                this.expanded = this.layout === "expanded"; // Use this.expanded
                this.stackToasts();
            },
            showToast() {
                event.stopPropagation();
                if (event.detail.position) {
                    this.position = event.detail.position;
                }
                this.toasts.unshift({
                    id: "toast-" + Math.random().toString(16).slice(2),
                    show: false,
                    message: event.detail.message,
                    description: event.detail.description,
                    type: event.detail.type,
                    html: event.detail.html,
                });
            },
        };
    }
}
