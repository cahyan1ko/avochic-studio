<script setup lang="ts">
import AuthBase from "@/layouts/AuthLayout.vue";
import InputError from "@/components/InputError.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { Head } from "@inertiajs/vue3";
import { ref, reactive } from "vue";
import axios from "axios";

const step = ref<"register" | "verify">("register");

const otpForm = reactive({ otp: "" });

const message = ref("");

interface RegisterForm {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

const registerForm = reactive<RegisterForm>({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const submitRegister = async () => {
  message.value = "";
  try {
    await axios.post("/api/register", registerForm);
    step.value = "verify";
  } catch (err: any) {
    message.value = err.response?.data?.message || "Terjadi kesalahan saat register";
  }
};

// Submit OTP verify
const submitOtp = async () => {
  try {
    message.value = "";
    const res = await axios.post("/api/verify-otp", {
      email: registerForm.email,
      otp: otpForm.otp,
    });
    localStorage.setItem("token", res.data.token);
    alert("Verifikasi berhasil! Token tersimpan.");
    window.location.href = "/"; // Redirect ke dashboard
  } catch (err: any) {
    message.value =
      err.response?.data?.message || "Terjadi kesalahan saat verifikasi OTP";
  }
};
</script>

<template>
  <AuthBase
    title="Create an account"
    description="Enter your details below to create your account"
  >
    <Head title="Register" />

    <div class="max-w-xl w-full mx-auto mt-12 p-8 bg-white rounded shadow-lg">
      <!-- STEP REGISTER -->
      <div v-if="step === 'register'" class="flex flex-col gap-6">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input
              id="name"
              type="text"
              placeholder="Full name"
              v-model="registerForm.name"
            />
          </div>

          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              type="email"
              placeholder="email@example.com"
              v-model="registerForm.email"
            />
          </div>

          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input
              id="password"
              type="password"
              placeholder="Password"
              v-model="registerForm.password"
            />
          </div>

          <div class="grid gap-2">
            <Label for="password_confirmation">Confirm password</Label>
            <Input
              id="password_confirmation"
              type="password"
              placeholder="Confirm password"
              v-model="registerForm.password_confirmation"
            />
          </div>

          <Button class="w-full mt-2" @click="submitRegister"> Daftar </Button>
        </div>
      </div>

      <!-- STEP VERIFY OTP -->
      <div v-else-if="step === 'verify'" class="flex flex-col gap-4">
        <h2 class="text-2xl font-bold mb-4 text-center">Masukkan OTP</h2>
        <p class="mb-4 text-center">
          Kode OTP telah dikirim ke: {{ registerForm.email }}
        </p>
        <Input type="text" placeholder="6-digit OTP" v-model="otpForm.otp" />
        <Button class="w-full mt-2" @click="submitOtp">Verifikasi</Button>
      </div>

      <div v-if="message" class="mt-4 text-red-600 text-center">{{ message }}</div>
    </div>
  </AuthBase>
</template>
