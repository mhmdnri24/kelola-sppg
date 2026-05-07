<div class="bg-white p-6 rounded shadow" id="app">
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" :class="getFieldClass('nama')" placeholder="Input nama katalog..." v-model="form.nama" @blur="validateField('nama')">
            <p v-if="errors.nama" class="text-red-500 text-xs mt-1">@{{ errors.nama }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
            {{-- <input type="text" :class="getFieldClass('supplier')" placeholder="Input supplier..." v-model="form.supplier" @blur="validateField('supplier')"> --}}
            <select :class="getFieldClass('supplier_id')" v-model="form.supplier_id" @blur="validateField('supplier_id')">
                <option value="">Pilih Supplier</option>
                <template v-for="supplier in suppliers" :key="supplier.id">
                    <option :value="supplier.id">@{{ supplier.name }}</option>
                </template>
            </select>
            <p v-if="errors.supplier_id" class="text-red-500 text-xs mt-1">@{{ errors.supplier_id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
            <input type="text" :class="getFieldClass('harga')" placeholder="Input harga..." v-model="form.harga" @blur="validateField('harga')" @input="formatCurrency">
            <p v-if="errors.harga" class="text-red-500 text-xs mt-1">@{{ errors.harga }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
            <input type="text" :class="getFieldClass('stok')" placeholder="Input stok..." v-model="form.stok" @blur="validateField('stok')">
            <p v-if="errors.stok" class="text-red-500 text-xs mt-1">@{{ errors.stok }}</p>
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea :class="getFieldClass('deskripsi')" placeholder="Input deskripsi..." v-model="form.deskripsi" rows="4"></textarea>
    </div>

      <div class="mb-6">
       <input type="checkbox" id="is_terbit" v-model="form.is_terbit" class="mr-2 leading-tight">
       <label for="is_terbit" class="text-sm text-gray-700">Terbitkan Katalog</label>
    </div>

    <div class="flex justify-end gap-3">
        <button @click="handleSave" class="bg-green-main hover:bg-green-light text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-[0_2px_8px_rgba(40,167,69,0.35)] transition-all hover:-translate-y-px">
            Save
        </button>
        <button @click="handleReset" type="button" class="bg-gray-500 hover:bg-gray-600 text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-[0_2px_8px_rgba(40,167,69,0.35)] transition-all hover:-translate-y-px">
            Reset
        </button>
    </div>
</div>

@section('js')

<script>
    createApp({
        data() {
            return {
                baseInputClass: "block w-full border shadow-md border-gray-300 rounded-md shadow-sm pl-4 py-2 focus:ring-green-main focus:border-green-main sm:text-sm",
                form: {
                    nama: '',
                    supplier_id: '',
                    harga: '',
                    stok: '',
                    deskripsi: '',
                    is_terbit: true
                },
                errors: {},
                rules: {
                    nama: {
                        required: true,
                        minLength: 3
                    },
                    supplier_id: {
                        required: true,
                        numeric: true
                    },
                    harga: {
                        required: true,
                        numeric: true
                    },
                    stok: {
                        required: true,
                        numeric: true,
                        integer: true
                    },
                },
                suppliers: []
            }
        },
        mounted() {
            this.loadEditData();
            this.getSuppliers();
        },
        methods: {
            formatCurrency() {
                // Remove non-numeric characters
                let value = this.form.harga.toString().replace(/\D/g, '');
                this.form.harga = value;
            },

            getFieldClass(fieldName) {
                const hasError = this.errors[fieldName];
                const errorClass = hasError ? 'border-red-500 bg-red-50' : 'border-gray-300';
                return `${this.baseInputClass} ${errorClass}`;
            },

            validateField(fieldName) {
                const value = this.form[fieldName];
                const fieldRules = this.rules[fieldName];

                if (!fieldRules) return;

                // Check required
                if (fieldRules.required && (!value || value.toString().trim() === '')) {
                    this.errors[fieldName] = 'This field is required';
                    return;
                }

                // Check minLength
                if (fieldRules.minLength && value && value.toString().length < fieldRules.minLength) {
                    this.errors[fieldName] = `Minimum ${fieldRules.minLength} characters required`;
                    return;
                }

                // Check numeric
                if (fieldRules.numeric && value && isNaN(value)) {
                    this.errors[fieldName] = 'This field must be a number';
                    return;
                }

                // Check integer
                if (fieldRules.integer && value && !Number.isInteger(parseFloat(value))) {
                    this.errors[fieldName] = 'This field must be an integer';
                    return;
                }

                // Clear error if validation passes
                delete this.errors[fieldName];
            },

            validateAll() {
                this.errors = {};
                Object.keys(this.rules).forEach(fieldName => {
                    this.validateField(fieldName);
                });
                return Object.keys(this.errors).length === 0;
            },

            loadEditData() {
                @if(isset($katalog))
                this.form = {
                    nama: '{{ $katalog->nama }}',
                    supplier_id: '{{ $katalog->supplier_id }}',
                    harga: '{{ $katalog->harga }}',
                    stok: '{{ $katalog->stok }}',
                    deskripsi: '{{ $katalog->deskripsi }}',
                    is_terbit: {{ $katalog->is_terbit ? 'true' : 'false' }}
                };
                @endif
            },

            handleSave() {
                if (this.validateAll()) {
                    let url = "{{ route('katalog.store') }}";
                    let method = 'POST';
                    let formData = {
                        ...this.form
                    };

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    @if(isset($katalog))
                    url = "{{ route('katalog.update', $katalog->id) }}";
                    method = 'PUT';
                    @endif

                    formData._token = csrfToken;

                    Swal.fire({
                        title: 'Menyimpan...',
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 419) {
                                    throw new Error('Session expired. Please refresh the page and try again.');
                                }
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = "{{ route('katalog') }}";
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message || 'Terjadi kesalahan',
                                    icon: 'error',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Terjadi kesalahan saat menyimpan',
                                icon: 'error',
                                confirmButtonColor: '#28a745',
                                confirmButtonText: 'OK'
                            });
                            console.error('Error:', error);
                        });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please fix the errors in the form',
                        icon: 'error',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'OK'
                    });
                }
            },

            handleReset() {
                this.form = {
                    nama: '',
                    supplier_id: '',
                    harga: '',
                    stok: '',
                    deskripsi: '',
                };
                this.errors = {};
            },
            getSuppliers() {
                fetch("{{ route('suppliers.data') }}")
                    .then(response => response.json())
                    .then(data => {
                        this.suppliers = data || [];
                        console.log('Suppliers:', data);
                    })
                    .catch(error => {
                        console.error('Error fetching suppliers:', error);
                    });
            },
        }
    }).mount('#app')
</script>
@endsection