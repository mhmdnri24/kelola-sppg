<div class="flex gap-3" id="app">
    <div class="bg-white p-6 pb-10 rounded shadow w-[74%]">

        <div class="grid grid-cols-3 gap-3 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dapur <span class="text-red-500">*</span>
                </label>
                <select :class="getFieldClass('dapur_id')" v-model="form.dapur_id" @blur="validateField('dapur_id')">
                    <option value="">Pilih Dapur</option>
                    <template v-for="dapur in dapurOptions" :key="dapur.id">
                        <option :value="dapur.id">@{{ dapur.name }}</option>

                    </template>
                </select>
                <p v-if="errors.dapur_id" class="text-red-500 text-xs mt-1">@{{ errors.dapur_id }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggaran <span
                        class="text-red-500">*</span></label>
                <input type="text" :class="getFieldClass('nama_anggaran')" placeholder="Input nama anggaran..."
                    v-model="form.nama_anggaran" @blur="validateField('nama_anggaran')">
                <p v-if="errors.nama_anggaran" class="text-red-500 text-xs mt-1">@{{ errors.nama_anggaran }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span
                        class="text-red-500">*</span></label>
                <select :class="getFieldClass('kategori')" v-model="form.kategori" @blur="validateField('kategori')">
                    <option value="UMUM">UMUM</option>
                    <option value="SISWA">SISWA</option>
                    <option value="3B">3B</option>

                </select>
                <p v-if="errors.kategori" class="text-red-500 text-xs mt-1">@{{ errors.kategori }}</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">PB <span
                        class="text-red-500">*</span></label>
                <input type="text" :class="getFieldClass('pm_pb')" placeholder="Input porsi besar..."
                    v-model="form.pm_pb" @blur="validateField('pm_pb')" @input="calculateFields">
                <p v-if="errors.pm_pb" class="text-red-500 text-xs mt-1">@{{ errors.pm_pb }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">PK</label>
                <input type="text" :class="getFieldClass('pm_pk')" placeholder="Input porsi kecil..."
                    v-model="form.pm_pk" @blur="validateField('pm_pk')" @input="calculateFields">
                <p v-if="errors.pm_pk" class="text-red-500 text-xs mt-1">@{{ errors.pm_pk }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pagu PB <span
                        class="text-red-500">*</span></label>
                <input type="text" :class="getFieldClass('pagu_pb')" placeholder="Input pagu PB..."
                    v-model="form.pagu_pb" @blur="validateField('pagu_pb')" @input="calculateFields">
                <p v-if="errors.pagu_pb" class="text-red-500 text-xs mt-1">@{{ errors.pagu_pb }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pagu PK</label>
                <input type="text" :class="getFieldClass('pagu_pk')" placeholder="Input pagu PK..."
                    v-model="form.pagu_pk" @blur="validateField('pagu_pk')" @input="calculateFields">
                <p v-if="errors.pagu_pk" class="text-red-500 text-xs mt-1">@{{ errors.pagu_pk }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">HPP PB <span
                        class="text-red-500">*</span></label>
                <input type="text" :class="getFieldClass('hpp_pb')" placeholder="Input HPP PB..."
                    v-model="form.hpp_pb" @blur="validateField('hpp_pb')" @input="calculateFields">
                <p v-if="errors.hpp_pb" class="text-red-500 text-xs mt-1">@{{ errors.hpp_pb }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">HPP PK</label>
                <input type="text" :class="getFieldClass('hpp_pk')" placeholder="Input HPP PK..."
                    v-model="form.hpp_pk" @blur="validateField('hpp_pk')" @input="calculateFields">
                <p v-if="errors.hpp_pk" class="text-red-500 text-xs mt-1">@{{ errors.hpp_pk }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Aktif</label>
                <input type="date" :class="getFieldClass('active_date')" placeholder="Input pagu PK..."
                    v-model="form.active_date" @blur="validateField('active_date')" @input="calculateFields">
                <p v-if="errors.active_date" class="text-red-500 text-xs mt-1">@{{ errors.active_date }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Hari Aktif <span
                        class="text-red-500">*</span></label>
                <input type="number" :class="getFieldClass('jumlah_hari')" placeholder="Input Jumlah Hari Aktif..."
                    v-model="form.jumlah_hari" @blur="validateField('jumlah_hari')" @input="calculateFields">
                <p v-if="errors.jumlah_hari" class="text-red-500 text-xs mt-1">@{{ errors.jumlah_hari }}</p>
            </div>

        </div>

        <div class="flex justify-end gap-3">
            <button @click="handleSave"
                class="bg-green-main hover:bg-green-light text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-[0_2px_8px_rgba(40,167,69,0.35)] transition-all hover:-translate-y-px">
                Save
            </button>
            <button v-if="formType=='create'" @click="handleReset" type="button"
                class="bg-gray-500 hover:bg-gray-600 text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-[0_2px_8px_rgba(40,167,69,0.35)] transition-all hover:-translate-y-px">
                Reset
            </button>
        </div>

    </div>
    <div class="bg-white w-1/4 rounded shadow p-4">

        <div class="rounded-xl w-full p-4 bg-red-100 border border-red-300 p-3 mb-3">
            <h3 class="text-lg font-bold text-red-600 mb-2">Penerima Manfaat</h3>
            <h3 class="text-red-500">@{{ calculatedFields.penerima_manfaat }}</h3>
        </div>

        <div class="rounded-xl w-full p-4 bg-green-100 border border-green-300 p-3 mb-3">
            <h3 class="text-lg font-bold text-green-600 mb-2">Limit Belanja</h3>
            <h3 class="text-green-500">@{{ formatcurrency(calculatedFields.limit_belanja) }}</h3>
        </div>


        <div class="grid grid-cols-2 gap-2">
            <div class="rounded-xl w-full p-4 bg-blue-100 border border-blue-300 p-3 ">
                <h3 class="text-lg font-bold text-blue-600 mb-2">Pagu Anggaran</h3>
                <h3 class="text-blue-500">@{{ formatcurrency(calculatedFields.pagu_anggaran) }}</h3>
            </div>

            <div class="rounded-xl w-full p-4 bg-yellow-100 border border-yellow-300 p-3 ">
                <h3 class="text-lg font-bold text-yellow-600 mb-2">Margin</h3>
                <h3 class="text-yellow-500">@{{ formatcurrency(calculatedFields.margin) }}</h3>
            </div>

        </div>
    </div>
</div>

@section('js')
<script>
    createApp({
        data() {
            return {
                baseInputClass: "block w-full border shadow-md border-gray-300 rounded-md shadow-sm pl-4 py-2 focus:ring-green-main focus:border-green-main sm:text-sm",
                formType: 'create', // or 'edit'
                form: {
                    dapur_id: '',
                    kategori: '',
                    nama_anggaran: '',
                    pm_pb: '',
                    pm_pk: '',
                    pagu_pb: '',
                    pagu_pk: '',
                    hpp_pb: '',
                    hpp_pk: '',
                    active_date: '',
                    jumlah_hari: 2,
                },
                calculatedFields: {
                    penerima_manfaat: 0,
                    limit_belanja: 0,
                    pagu_anggaran: 0,
                    margin: 0,
                },
                dapurOptions: [],
                errors: {},
                rules: {
                    dapur_id: {
                        required: true
                    },
                    kategori: {
                        required: true
                    },
                    nama_anggaran: {
                        required: true,
                        minLength: 3
                    },
                    pm_pb: {
                        required: true,
                        numeric: true
                    },
                    pm_pk: {
                        numeric: true
                    },
                    pagu_pb: {
                        required: true,
                        numeric: true
                    },
                    pagu_pk: {
                        numeric: true
                    },
                    hpp_pb: {
                        required: true,
                        numeric: true
                    },
                    hpp_pk: {
                        numeric: true
                    },
                }
            }
        },
        mounted() {
            this.loadEditData();
            this.getDapurOptions();
        },
        methods: {
            calculateFields() {
                const pm_pb = parseFloat(this.form.pm_pb) || 0;
                const pm_pk = parseFloat(this.form.pm_pk) || 0;
                const pagu_pb = parseFloat(this.form.pagu_pb) || 0;
                const pagu_pk = parseFloat(this.form.pagu_pk) || 0;
                const hpp_pb = parseFloat(this.form.hpp_pb) || 0;
                const hpp_pk = parseFloat(this.form.hpp_pk) || 0;

                this.calculatedFields.penerima_manfaat = pm_pb + pm_pk;
                this.calculatedFields.limit_belanja = (pm_pb * hpp_pb) + (pm_pk * hpp_pk);
                this.calculatedFields.pagu_anggaran = (pagu_pb * pm_pb) + (pagu_pk * pm_pk);
                this.calculatedFields.margin = this.calculatedFields.pagu_anggaran - this.calculatedFields
                    .limit_belanja;
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
                @if(isset($anggaran))
                this.form = {
                    dapur_id: '{{ $anggaran->dapur_id }}',
                    kategori: '{{ $anggaran->kategori }}',
                    nama_anggaran: '{{ $anggaran->nama_anggaran }}',
                    pm_pb: '{{ $anggaran->pm_pb }}',
                    pm_pk: '{{ $anggaran->pm_pk }}',
                    pagu_pb: '{{ $anggaran->pagu_pb }}',
                    pagu_pk: '{{ $anggaran->pagu_pk }}',
                    hpp_pb: '{{ $anggaran->hpp_pb }}',
                    hpp_pk: '{{ $anggaran->hpp_pk }}',
                    active_date: '{{ $anggaran->active_date }}',
                    jumlah_hari: '{{ $anggaran->jumlah_hari }}'
                };
                this.formType = 'edit';
                this.calculateFields();
                @endif
            },
            handleSave() {
                if (this.validateAll()) {
                    let url = "{{ route('anggaran.store') }}";
                    let method = 'POST';
                    let formData = {
                        ...this.form
                    };

                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    // If in edit mode
                    @if(isset($anggaran))
                    url = "{{ route('anggaran.update', $anggaran->id) }}";
                    method = 'PUT';
                    @endif

                    // Add CSRF token to form data
                    formData._token = csrfToken;

                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    const fetchOptions = {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    };

                    fetch(url, fetchOptions)
                        .then(response => {
                            if (!response.ok) {
                                // Handle non-200 responses
                                if (response.status === 419) {
                                    throw new Error(
                                        'Session expired. Please refresh the page and try again.');
                                }
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            const res = response.json();
                            console.log(res)
                            return res;
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
                                    window.location.href = "{{ route('anggaran') }}";
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
                    dapur_id: '',
                    kategori: '',
                    nama_anggaran: '',
                    pm_pb: '',
                    pm_pk: '',
                    pagu_pb: '',
                    pagu_pk: '',
                    hpp_pb: '',
                    hpp_pk: '',
                };
                this.errors = {};
            },
            getDapurOptions() {
                fetch("{{ route('dapur.data') }}")
                    .then(response => response.json())
                    .then(data => {

                        this.dapurOptions = data || [];
                    })
                    .catch(error => {
                        console.error('Error fetching dapur options:', error);
                    });
            },
            formatcurrency(value) {
                if (isNaN(value)) return value;
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(value);
            }
        }
    }).mount('#app')
</script>
@endsection