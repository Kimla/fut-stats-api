<template>
  <form 
    @submit.prevent="login"
    class="container px-6 mx-auto"
  >
    <label class="block mb-4">
        <span class="text-gray-700">Email</span>
        <input 
            v-model="email"
            class="form-input mt-1 block w-full"
            type="email"
        >
    </label>

    <label class="block mb-4">
        <span class="text-gray-700">Password</span>
        <input 
            v-model="password"
            class="form-input mt-1 block w-full"
            type="password"
        >
    </label>

    <div class="text-right">
        <button 
            class="bg-blue-500 text-white px-6 py-3 rounded text-sm uppercase font-bold tracking-widest"
            type="submit"
        >
            Login
        </button>
    </div>
  </form>
</template>

<script>
export default {
    data: () => ({
        email: null,
        password: null
    }),

    methods: {
        async login() {
            const res = await postData('/api/auth/login', {
                email: this.email,
                password: this.password
            });

            console.log(res);
        }
    }
}

async function postData(url = '', data = {}) {
  // Default options are marked with *
  const response = await fetch(url, {
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    mode: 'cors', // no-cors, *cors, same-origin
    cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    credentials: 'same-origin', // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/json'
    },
    redirect: 'follow', // manual, *follow, error
    referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    body: JSON.stringify(data) // body data type must match "Content-Type" header
  });
  return response.json(); // parses JSON response into native JavaScript objects
}
</script>

<style>

</style>