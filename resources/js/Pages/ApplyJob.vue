<template>
  <app-layout>
    <div class="container mt-10 mx-auto py-12">
      <div class="flex flex-wrap justify-between">


        <!-- Left Content -->

        <div class="w-full lg:w-2/3 mb-10 lg:mb-0">
          <!-- Job single -->
          <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <div class="flex items-center mb-4">
              <div class="w-24 h-24 mr-4">
                <img :src="job.image" alt="" class="w-full h-full object-cover rounded-full">
              </div>
              <div>
                <h4 class="text-2xl font-semibold">{{ job.title }}</h4>
                <ul class="flex space-x-2 text-gray-600">
                  <li>{{ job.experience.toUpperCase() }}</li>
                  <li><i class="fas fa-map-marker-alt"></i> {{ job.location }}</li>
                  <li><i class="fas fa-industry"></i> {{ job.category.name }}</li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Job single End -->

          <div v-if="success" class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4">Your Application Has Been Submitted!</h3>
            <p class="mb-2"><strong>Tracking Code:</strong> {{ trackingCode }}</p>
            <p class="mb-2"><strong>Job Title:</strong> {{ jobTitle }}</p>
            <p class="mb-2"><strong>Applicant Name:</strong> {{ applicantName }}</p>
            <p class="mb-2"><strong>Tracking URL:</strong> <a :href="trackingUrl" class="text-blue-500">{{ trackingUrl }}</a></p>
          </div>

          <div v-else class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h3 class="text-center text-xl font-semibold mb-4">Apply for Job</h3>
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <div class="grid grid-cols-1 gap-6">
                <div v-for="(field, index) in fields" :key="index" class="flex flex-col">
                  <label :for="field.id" class="font-semibold mb-2">{{ field.label }}</label>
                  <input v-if="field.type !== 'textarea' && field.type !== 'select' && field.type !== 'radio' && field.type !== 'file'" :type="field.type" :name="field.name" :id="field.id" v-model="form[field.name]" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" :placeholder="field.placeholder" :required="field.required">
                  <textarea v-if="field.type === 'textarea'" :name="field.name" :id="field.id" v-model="form[field.name]" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" :placeholder="field.placeholder" :rows="3" :required="field.required"></textarea>
                  <select v-if="field.type === 'select'" :name="field.name" :id="field.id" v-model="form[field.name]" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" :required="field.required">
                    <option v-for="option in field.options" :key="option.value" :value="option.value">{{ option.label }}</option>
                  </select>
                  <div v-if="field.type === 'radio'" class="flex items-center space-x-4">
                    <div v-for="option in field.options" :key="option.value" class="flex items-center">
                      <input :type="field.type" :name="field.name" :id="option.id" v-model="form[field.name]" :value="option.value" class="mr-2">
                      <label :for="option.id">{{ option.label }}</label>
                    </div>
                  </div>
                  <input v-if="field.type === 'file'" :type="field.type" :name="field.name" :id="field.id" @change="handleFileChange" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" :placeholder="field.placeholder" :required="field.required">
                </div>
              </div>
              <div class="text-center mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Application</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Content -->
        <div class="w-full lg:w-1/3 bg-white shadow-lg rounded-lg p-6">
          <h4 class="text-xl font-semibold mb-4">Job Overview</h4>
          <div class="grid grid-cols-2 gap-2 text-gray-600">
            <span class="font-semibold">Posted date:</span>
            <span>{{ job.created_at_formatted }}</span>
            <span class="font-semibold">Location:</span>
            <span>{{ job.location }}</span>
            <span class="font-semibold">Job nature:</span>
            <span>{{ job.work_hours }}</span>
            <span class="font-semibold">Application date:</span>
            <span>{{ job.application_deadline }}</span>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
  components: {
    AppLayout,
    InertiaLink: Link,
  },
  props: {
    job: {
      type: Object,
      required: true
    },
    errors: Object,
    flash: {
      type: Object,
      default: () => ({}),
    },

  },
  setup(props) {
    const form = reactive({
      first_name: '',
      last_name: '',
      email: '',
      phone_number: '',
      old_company: '',
      date_of_birth: '',
      place_of_birth: '',
      education: '',
      major: '',
      gender: '',
      join_date: '',
      address: '',
      self_description: '',
      linkedin_url: '',
      job_source: '',
      cv_path: null,
    });

    const success = ref(props.flash.success || false);
    const trackingCode = ref(props.flash.tracking_code || '');
    const jobTitle = ref(props.flash.job_title || '');
    const applicantName = ref(props.flash.applicant_name || '');
    const trackingUrl = ref(props.flash.tracking_url || '');

    const fields = [
      { label: 'First Name', name: 'first_name', type: 'text', id: 'first_name', placeholder: 'First Name', required: true },
      { label: 'Last Name', name: 'last_name', type: 'text', id: 'last_name', placeholder: 'Last Name', required: true },
      { label: 'Email Address', name: 'email', type: 'email', id: 'email', placeholder: 'Email Address', required: true },
      { label: 'Phone', name: 'phone_number', type: 'text', id: 'phone', placeholder: 'Phone', required: true },
      { label: 'Current Company', name: 'old_company', type: 'text', id: 'old_company', placeholder: 'Current Company', required: true },
      { label: 'Date of Birth', name: 'date_of_birth', type: 'date', id: 'date_of_birth', placeholder: 'Date of Birth', required: true },
      { label: 'Place of Birth', name: 'place_of_birth', type: 'text', id: 'place_of_birth', placeholder: 'Place of Birth', required: true },
      { label: 'Last Education', name: 'education', type: 'select', id: 'education', options: [{ value: '', label: 'Last Education' }, { value: 'SMA', label: 'SMA' }, { value: 'D3', label: 'D3' }, { value: 'S1', label: 'S1' }, { value: 'S2', label: 'S2' }], required: true },
      { label: 'Major', name: 'major', type: 'text', id: 'major', placeholder: 'Major', required: true },
      { label: 'Gender', name: 'gender', type: 'radio', id: 'gender', options: [{ value: '0', label: 'Male', id: 'male' }, { value: '1', label: 'Female', id: 'female' }], required: true },
      { label: 'Join Date', name: 'join_date', type: 'select', id: 'join_date', options: Array.from({ length: 31 }, (v, k) => ({ value: k + 1, label: `${k + 1} Hari` })), required: true },
      { label: 'Current Address', name: 'address', type: 'textarea', id: 'address', placeholder: 'Current Address', required: true },
      { label: 'Self Description', name: 'self_description', type: 'textarea', id: 'self_description', placeholder: 'Self Description', required: true },
      { label: 'LinkedIn URL', name: 'linkedin_url', type: 'text', id: 'linkedin_url', placeholder: 'LinkedIn URL', required: true },
      { label: 'Job Source', name: 'job_source', type: 'select', id: 'job_source', options: [{ value: '', label: 'Where did you find this job?' }, { value: 'Instagram', label: 'Instagram' }, { value: 'Facebook', label: 'Facebook' }, { value: 'Twitter', label: 'Twitter' }, { value: 'Linkedin', label: 'Linkedin' }, { value: 'Jobstreet', label: 'Jobstreet' }, { value: 'Karir.com', label: 'Karir.com' }, { value: 'Jobindo.com', label: 'Jobindo.com' }], required: true },
      { label: 'Upload CV', name: 'cv_path', type: 'file', id: 'cv_path', placeholder: 'Upload CV', required: true },
    ];

    const handleFileChange = (e) => {
      form.cv_path = e.target.files[0];
    };

    const submit = () => {
      const formData = new FormData();
      Object.keys(form).forEach(key => {
        formData.append(key, form[key]);
      });

      Inertia.post(`/job-vacancies/${props.job.id}/apply`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
        onSuccess: () => {
          // success.value = true;
          // trackingCode.value = page.props.flash.tracking_code;
          // jobTitle.value = page.props.flash.job_title;
          // applicantName.value = page.props.flash.applicant_name;
          // console.log(page);

          Swal.fire({
            title: 'Success',
            text: 'Your application has been submitted successfully.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          });
        },
        onError: (errors) => {
          console.error(errors);
        }
      });
    };

    return {
      form,
      fields,
      success,
      trackingCode,
      jobTitle,
      applicantName,
      trackingUrl,
      submit,
      handleFileChange
    };
  },
};
</script>

<style scoped>
/* Tambahkan styling Tailwind CSS sesuai kebutuhan */
</style>
