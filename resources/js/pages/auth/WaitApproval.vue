<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps<{
    status: string;
}>();
</script>

<template>
    <Head title="Cuenta en revisión" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-gray-100 p-6">
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-md">
            <div class="mb-6 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100">
                    <span class="text-2xl">⏳</span>
                </div>

                <div v-if="status === 'PENDING'">
                    <h2 class="text-2xl font-bold text-gray-800">¡Registro recibido!</h2>
                    <p class="mt-4 text-gray-600">
                        Tu cuenta se encuentra actualmente <b>en proceso de validación</b>.
                    </p>
                    <p class="mt-2 text-sm text-gray-500">
                        El Enlace Institucional de tu adscripción debe aprobar tu acceso para que puedas entrar al sistema CVIS.
                    </p>
                </div>

                <div v-else-if="status === 'REJECTED'">
                    <h2 class="text-2xl font-bold text-red-600">Acceso denegado</h2>
                    <p class="mt-4 text-gray-600">
                        Lo sentimos, tu solicitud de acceso ha sido rechazada por tu institución.
                    </p>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500 underline"
                >
                    Cerrar sesión para intentar con otra cuenta
                </Link>
            </div>
        </div>
    </div>
</template>