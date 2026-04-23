<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

// 1. Definición de tipos para TypeScript
interface Institution {
    id: number;
    name: string;
    short_name: string;
}

// 2. Props que recibimos desde FortifyServiceProvider
const props = defineProps<{
    dependencies: Institution[];
}>();

// 3. Inicialización del Formulario de Inertia
const form = useForm({
    name: '',
    email: '',
    curp: '',
    dependency_id: '',  // Solo para control visual del primer select
    institution_id: '', // Este es el que se guarda en la DB
    password: '',
    password_confirmation: '',
});

// 4. Estados para el selector dinámico
const institutions = ref<Institution[]>([]);
const loadingInstitutions = ref(false);

// 5. Función para cargar instituciones hijas según la dependencia
const fetchInstitutions = async (parentId: string) => {
    form.dependency_id = parentId; // Sincronizamos el valor
    form.institution_id = '';      // Reiniciamos la institución si cambia la dependencia
    
    if (!parentId) {
        institutions.value = [];
        return;
    }

    loadingInstitutions.value = true;
    try {
        const response = await fetch(`/api/institutions/${parentId}`);
        if (!response.ok) throw new Error('Error en la red');
        institutions.value = await response.json();
    } catch (error) {
        console.error("No se pudieron cargar las instituciones:", error);
    } finally {
        loadingInstitutions.value = false;
    }
};

// 6. Envío del formulario
const submit = () => {
    form.post(store.url(), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        
        // Esto asegura que si hay éxito, Inertia refresque la navegación 
        // y el Middleware tome el control
        onSuccess: () => {
             // Forzamos una visita al dashboard para que el Middleware 
             // lo mande a la pantalla de "WaitApproval"
            window.location.href = '/dashboard'; 
        },
    });
};

defineOptions({
    layout: {
        title: 'Registro CVIS',
        description: 'Solicita tu acceso al sistema controlado del CVIS',
    },
});
</script>

<template>
    <Head title="Registro CVIS" />

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-6">
            
            <div class="grid gap-2">
                <Label for="name">Nombre Completo</Label>
                <Input id="name" v-model="form.name" type="text" required autofocus placeholder="Nombre completo" />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Correo Institucional</Label>
                <Input id="email" v-model="form.email" type="email" required placeholder="email@ejemplo.com" />
                <InputError :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="curp">CURP</Label>
                <Input id="curp" v-model="form.curp" type="text" required class="uppercase" maxlength="18" placeholder="18 caracteres" />
                <InputError :message="form.errors.curp" />
            </div>

            <div class="grid gap-2">
                <Label for="dependency">Dependencia (Cabeza de Sector)</Label>
                <select 
                    id="dependency"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                    @change="(e: Event) => fetchInstitutions((e.target as HTMLSelectElement).value)"
                >
                    <option value="">Selecciona una opción...</option>
                    <option v-for="dep in dependencies" :key="dep.id" :value="dep.id">
                        {{ dep.name }}
                    </option>
                </select>
            </div>

            <div class="grid gap-2" v-show="institutions.length > 0 || loadingInstitutions">
                <Label for="institution_id">Institución de Adscripción</Label>
                <select 
                    id="institution_id"
                    v-model="form.institution_id"
                    required
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                    <option value="">{{ loadingInstitutions ? 'Cargando...' : 'Selecciona tu institución...' }}</option>
                    <option v-for="inst in institutions" :key="inst.id" :value="inst.id">
                        {{ inst.name }}
                    </option>
                </select>
                <InputError :message="form.errors.institution_id" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Contraseña</Label>
                <PasswordInput id="password" v-model="form.password" required placeholder="Contraseña" />
                <InputError :message="form.errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirmar Contraseña</Label>
                <PasswordInput id="password_confirmation" v-model="form.password_confirmation" required placeholder="Repetir contraseña" />
            </div>

            <Button type="submit" class="mt-2 w-full" :disabled="form.processing">
                <Spinner v-if="form.processing" />
                Solicitar Registro
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            ¿Ya tienes cuenta?
            <TextLink :href="login()" class="underline underline-offset-4">Inicia sesión</TextLink>
        </div>
    </form>
</template>
