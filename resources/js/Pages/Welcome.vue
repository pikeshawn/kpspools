<!--
  This example requires Tailwind CSS v2.0+

  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ]
  }
  ```
-->
<template>
    <div class="bg-white">
        <header>
            <Popover class="relative" style="background-color: black">
                <div class="flex justify-between items-center max-w-7xl mx-auto px-4 py-6 sm:px-6 md:justify-start md:space-x-10 lg:px-8">
                    <div class="flex justify-start lg:w-0 lg:flex-1">
                        <a href="#">
                            <span class="sr-only">Workflow</span>
                            <img class="w-auto w-2/5" src="/img/Original.png" alt="" />
                        </a>
                    </div>
                    <div class=" md:flex items-center justify-end md:flex-1 lg:w-0">
                        <a href="/dashboard" v-if="$page.props.user" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                            Dashboard
                        </a>
                        <a v-else :href="route('login')" class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">
                            Sign in
                        </a>
                    </div>
                    <br>
                </div>



                <transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="duration-100 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
                    <PopoverPanel focus class="absolute z-30 top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
                        <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
                            <div class="pt-5 pb-6 px-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-purple-600-to-indigo-600.svg" alt="Workflow" />
                                    </div>
                                    <div class="-mr-2">
                                        <PopoverButton class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                            <span class="sr-only">Close menu</span>
                                            <XIcon class="h-6 w-6" aria-hidden="true" />
                                        </PopoverButton>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <nav class="grid grid-cols-1 gap-7">
                                        <a v-for="item in solutions" :key="item.name" :href="item.href" class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-50">
                                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                                                <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />
                                            </div>
                                            <div class="ml-4 text-base font-medium text-gray-900">
                                                {{ item.name }}
                                            </div>
                                        </a>
                                    </nav>
                                </div>
                            </div>
                            <div class="py-6 px-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700">
                                        Pricing
                                    </a>
                                    <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700">
                                        Partners
                                    </a>
                                    <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700">
                                        Company
                                    </a>
                                </div>
                                <div class="mt-6">
                                    <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">
                                        Sign up
                                    </a>
                                    <p class="mt-6 text-center text-base font-medium text-gray-500">

                                        <inertia-link v-if="$page.props.user" href="/dashboard" class="text-sm text-gray-700 underline">
                                            Dashboard
                                        </inertia-link>

                                        <template v-else>
                                            <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
                                                Log in
                                            </inertia-link>
                                        </template>
                                    </p>
                                </div>



                                <div class="ml-5">
                                    <inertia-link v-if="$page.props.user" href="/dashboard" class="text-sm text-gray-700 underline">
                                        Dashboard
                                    </inertia-link>

                                    <template v-else>
                                        <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
                                            Log in
                                        </inertia-link>
                                    </template>
                                </div>



                            </div>
                        </div>
                    </PopoverPanel>
                </transition>
            </Popover>
        </header>

        <main>
            <!-- Hero section -->
<!--            <div class="relative">-->
<!--                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
<!--                    <div class="relative shadow-xl sm:rounded-2xl sm:overflow-hidden" style="height: 60rem; width: 83rem;">-->
<!--                        <div class="absolute inset-0">-->
<!--&lt;!&ndash;                            src="https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2830&q=80&sat=-100"&ndash;&gt;-->
<!--                            <img class="h-full w-full object-cover"-->
<!--                                 src="img/IMG_0338.jpeg"-->
<!--                                 alt="Current Flyer" />-->
<!--                            <div class="absolute inset-0 bg-gradient-to-r mix-blend-multiply" />-->
<!--                        </div>-->
<!--                        <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

            <!-- Alternating Feature Sections -->
            <div class="relative pt-16 pb-32 overflow-hidden">
                <div aria-hidden="true" class="absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-gray-100" />
                <div class="relative">
                    <div class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                        <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-16 lg:max-w-none lg:mx-0 lg:px-0">
                            <div>
                                <div>
                                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                        Starting at 100$ per month including chemicals
                                    </h2>
                                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                        480-703-4902
                                    </h2>
                                </div>
                                <div class="mt-6">
                                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                        Great Service Great Price!
                                    </h2>

                                    <p class="mt-4 text-lg text-gray-500">For over 25 years, our company has been the
                                        trusted name in the pool industry. With a legacy of excellence, we are committed
                                        to providing exceptional service that goes above and beyond. Our team takes pride
                                        in our super responsive customer service, ensuring that your questions and concerns
                                        are addressed promptly and with care. From pumps to filters and everything in between,
                                        our skilled technicians specialize in equipment repairs, ensuring that your pool
                                        functions flawlessly. What sets us apart is our extensive network of professionals,
                                        ready to assist you with any pool-related need. Whether it's pool remodeling,
                                        water treatment, or regular maintenance, our strong connections guarantee that
                                        you have access to the best solutions and expertise. Choose us as your pool
                                        partner and experience the unmatched expertise, responsive service, and
                                        comprehensive solutions that have made us an industry leader for over two decades.</p>

<!--                                    <p class="mt-4 text-lg text-gray-500">At KPS Pools LLC., we pride ourselves on delivering super responsive customer service. We understand the importance of addressing your concerns promptly, and our dedicated team is always ready to assist you. Whether you have a question, need guidance, or require urgent assistance, we are just a phone call away.</p>-->

<!--                                    <p class="mt-4 text-lg text-gray-500">In addition to our outstanding customer service, we specialize in equipment repairs. No matter the issue, our skilled technicians possess the knowledge and expertise to diagnose and fix any problem efficiently. From pumps and filters to heaters and automation systems, we've got you covered.</p>-->

<!--                                    <p class="mt-4 text-lg text-gray-500">What sets us apart is our extensive network of professionals who can help you with all your pool needs. Whether you're looking for pool remodeling, water treatment solutions, or regular maintenance services, we have established strong connections with industry experts. Count on us to connect you with the right professionals who can ensure your pool remains in pristine condition year-round.</p>-->

<!--                                    <p class="mt-4 text-lg text-gray-500">Choose KPS Pools LLC. for unparalleled experience, exceptional customer service, comprehensive equipment repairs, and access to a vast network of pool professionals. Experience the difference of working with a company that prioritizes your satisfaction and understands your pool needs like no other. Contact us today to get started on transforming your pool into a true oasis of enjoyment and relaxation.</p>-->
<!--                                    <div class="mt-6">-->
<!--                                        <a href="#" class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">-->
<!--                                            Get started today.-->
<!--                                        </a>-->
<!--                                    </div>-->
                                </div>
                            </div>
                            <div class="mt-8 border-t border-gray-200 pt-6">
                                <blockquote>
                                    <div>
                                        <p class="text-base text-gray-500">
                                            I take pride in building and providing the best possible experience for our customers.
                                            Our entire team strives to provide the utmost satisfaction in every service visit.
                                        </p>
                                    </div>
                                    <footer class="mt-3">
                                        <div class="flex items-center space-x-3">
<!--                                            <div class="flex-shrink-0">-->
<!--                                                <img class="h-6 w-6 rounded-full" src="https://images.unsplash.com/photo-1509783236416-c9ad59bae472?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80" alt="" />-->
<!--                                            </div>-->
                                            <div class="text-base font-medium text-gray-700">
                                                Shawn Pike, Owner
                                            </div>
                                        </div>
                                    </footer>
                                </blockquote>
                            </div>
                        </div>
                        <div class="mt-12 sm:mt-16 lg:mt-0">
                            <div class="pl-4 -mr-48 sm:pl-6 md:-mr-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                                <img class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:left-0 lg:h-full lg:w-auto lg:max-w-none" src="img/IMG_6444.jpeg" alt="Inbox user interface" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-24">
                    <div class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                        <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-32 lg:max-w-none lg:mx-0 lg:px-0 lg:col-start-2">
                            <div>
                                <div>
                  <span class="h-12 w-12 rounded-md flex items-center justify-center bg-gradient-to-r from-purple-600 to-indigo-600">
                    <SparklesIcon class="h-6 w-6 text-white" aria-hidden="true" />
                  </span>
                                </div>
                                <div class="mt-6">
                                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                        Rest Easy With Responsive Service
                                    </h2>
                                    <p class="mt-4 text-lg text-gray-500">
                                        Introducing our unparalleled responsive service that sets a new standard in
                                        customer satisfaction! At our company, we take pride in delivering prompt and
                                        reliable assistance to all our valued clients. With our lightning-fast response
                                        time, you can expect a text message reply within a remarkable 15 minutes from the
                                        moment you reach out to us. Our commitment to providing exceptional service is
                                        further reinforced by our service guarantee. If, for any reason, you are not
                                        completely satisfied with our service, we don't just stop there. We go the
                                        extra mile by sending our dedicated expert back to your doorstep on the very
                                        same day. Experience the epitome of customer care and let us exceed your
                                        expectations every step of the way.
                                    </p>
<!--                                    <div class="mt-6">-->
<!--                                        <a href="#" class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">-->
<!--                                            Get started-->
<!--                                        </a>-->
<!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="mt-12 sm:mt-16 lg:mt-0 lg:col-start-1">
                            <div class="pr-4 -ml-48 sm:pr-6 md:-ml-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                                <img class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:right-0 lg:h-full lg:w-auto lg:max-w-none" src="img/Charleston-Area-Public-Pools.png" alt="Customer profile user interface" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!--            &lt;!&ndash; Gradient Feature Section &ndash;&gt;-->
<!--            <div class="bg-gradient-to-r from-purple-800 to-indigo-700">-->
<!--                <div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 sm:pt-20 sm:pb-24 lg:max-w-7xl lg:pt-24 lg:px-8">-->
<!--                    <h2 class="text-3xl font-extrabold text-white tracking-tight">-->
<!--                        Inbox support built for efficiency-->
<!--                    </h2>-->
<!--                    <p class="mt-4 max-w-3xl text-lg text-purple-200">-->
<!--                        Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis. Blandit aliquam sit nisl euismod mattis in.-->
<!--                    </p>-->
<!--                    <div class="mt-12 grid grid-cols-1 gap-x-6 gap-y-12 sm:grid-cols-2 lg:mt-16 lg:grid-cols-4 lg:gap-x-8 lg:gap-y-16">-->
<!--                        <div v-for="feature in features" :key="feature.name">-->
<!--                            <div>-->
<!--                <span class="flex items-center justify-center h-12 w-12 rounded-md bg-white bg-opacity-10">-->
<!--                  <component :is="feature.icon" class="h-6 w-6 text-white" aria-hidden="true" />-->
<!--                </span>-->
<!--                            </div>-->
<!--                            <div class="mt-6">-->
<!--                                <h3 class="text-lg font-medium text-white">{{ feature.name }}</h3>-->
<!--                                <p class="mt-2 text-base text-purple-200">-->
<!--                                    {{ feature.description }}-->
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--            &lt;!&ndash; Stats section &ndash;&gt;-->
<!--            <div class="relative bg-gray-900">-->
<!--                <div class="h-80 absolute inset-x-0 bottom-0 xl:top-0 xl:h-full">-->
<!--                    <div class="h-full w-full xl:grid xl:grid-cols-2">-->
<!--                        <div class="h-full xl:relative xl:col-start-2">-->
<!--                            <img class="h-full w-full object-cover opacity-25 xl:absolute xl:inset-0" src="https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2830&q=80&sat=-100" alt="People working on laptops" />-->
<!--                            <div aria-hidden="true" class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-gray-900 xl:inset-y-0 xl:left-0 xl:h-full xl:w-32 xl:bg-gradient-to-r" />-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8 xl:grid xl:grid-cols-2 xl:grid-flow-col-dense xl:gap-x-8">-->
<!--                    <div class="relative pt-12 pb-64 sm:pt-24 sm:pb-64 xl:col-start-1 xl:pb-24">-->
<!--                        <h2 class="text-sm font-semibold tracking-wide uppercase">-->
<!--                            <span class="bg-gradient-to-r from-purple-300 to-indigo-300 bg-clip-text text-transparent">Valuable Metrics</span>-->
<!--                        </h2>-->
<!--                        <p class="mt-3 text-3xl font-extrabold text-white">Get actionable data that will help grow your business</p>-->
<!--                        <p class="mt-5 text-lg text-gray-300">Rhoncus sagittis risus arcu erat lectus bibendum. Ut in adipiscing quis in viverra tristique sem. Ornare feugiat viverra eleifend fusce orci in quis amet. Sit in et vitae tortor, massa. Dapibus laoreet amet lacus nibh integer quis. Eu vulputate diam sit tellus quis at.</p>-->
<!--                        <div class="mt-12 grid grid-cols-1 gap-y-12 gap-x-6 sm:grid-cols-2">-->
<!--                            <p v-for="item in metrics" :key="item.id">-->
<!--                                <span class="block text-2xl font-bold text-white">{{ item.stat }}</span>-->
<!--                                <span class="mt-1 block text-base text-gray-300"-->
<!--                                ><span class="font-medium text-white">{{ item.emphasis }}</span> {{ item.rest }}</span-->
<!--                                >-->
<!--                            </p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--            &lt;!&ndash; CTA Section &ndash;&gt;-->
<!--            <div class="bg-white">-->
<!--                <div class="max-w-4xl mx-auto py-16 px-4 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8 lg:flex lg:items-center lg:justify-between">-->
<!--                    <h2 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">-->
<!--                        <span class="block">Ready to get started?</span>-->
<!--                        <span class="block bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Get in touch or create an account.</span>-->
<!--                    </h2>-->
<!--                    <div class="mt-6 space-y-4 sm:space-y-0 sm:flex sm:space-x-5">-->
<!--                        <a href="#" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">-->
<!--                            Learn more-->
<!--                        </a>-->
<!--                        <a href="#" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-indigo-800 bg-indigo-50 hover:bg-indigo-100">-->
<!--                            Get started-->
<!--                        </a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </main>

        <footer class="bg-gray-50" aria-labelledby="footerHeading">
<!--            <h2 id="footerHeading" class="sr-only">Footer</h2>-->
            <div class="max-w-7xl mx-auto pt-16 pb-8 px-4 sm:px-6 lg:pt-24 lg:px-8">
                <div class="xl:grid xl:grid-cols-3 xl:gap-8">
<!--                    <div class="grid grid-cols-2 gap-8 xl:col-span-2">-->
<!--                        <div class="md:grid md:grid-cols-2 md:gap-8">-->
<!--                            <div>-->
<!--                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">-->
<!--                                    Solutions-->
<!--                                </h3>-->
<!--                                <ul class="mt-4 space-y-4">-->
<!--                                    <li v-for="item in footerNavigation.solutions" :key="item.name">-->
<!--                                        <a :href="item.href" class="text-base text-gray-500 hover:text-gray-900">-->
<!--                                            {{ item.name }}-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                            <div class="mt-12 md:mt-0">-->
<!--                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">-->
<!--                                    Support-->
<!--                                </h3>-->
<!--                                <ul class="mt-4 space-y-4">-->
<!--                                    <li v-for="item in footerNavigation.support" :key="item.name">-->
<!--                                        <a :href="item.href" class="text-base text-gray-500 hover:text-gray-900">-->
<!--                                            {{ item.name }}-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="md:grid md:grid-cols-2 md:gap-8">-->
<!--                            <div>-->
<!--                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">-->
<!--                                    Company-->
<!--                                </h3>-->
<!--                                <ul class="mt-4 space-y-4">-->
<!--                                    <li v-for="item in footerNavigation.company" :key="item.name">-->
<!--                                        <a :href="item.href" class="text-base text-gray-500 hover:text-gray-900">-->
<!--                                            {{ item.name }}-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                            <div class="mt-12 md:mt-0">-->
<!--                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">-->
<!--                                    Legal-->
<!--                                </h3>-->
<!--                                <ul class="mt-4 space-y-4">-->
<!--                                    <li v-for="item in footerNavigation.legal" :key="item.name">-->
<!--                                        <a :href="item.href" class="text-base text-gray-500 hover:text-gray-900">-->
<!--                                            {{ item.name }}-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="mt-12 xl:mt-0">-->
<!--                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">-->
<!--                            Subscribe to our newsletter-->
<!--                        </h3>-->
<!--                        <p class="mt-4 text-base text-gray-500">-->
<!--                            The latest news, articles, and resources, sent to your inbox weekly.-->
<!--                        </p>-->
<!--                        <form class="mt-4 sm:flex sm:max-w-md">-->
<!--                            <label for="emailAddress" class="sr-only">Email address</label>-->
<!--                            <input type="email" name="emailAddress" id="emailAddress" autocomplete="email" required="" class="appearance-none min-w-0 w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:placeholder-gray-400" placeholder="Enter your email" />-->
<!--                            <div class="mt-3 rounded-md sm:mt-0 sm:ml-3 sm:flex-shrink-0">-->
<!--                                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">-->
<!--                                    Subscribe-->
<!--                                </button>-->
<!--                            </div>-->
<!--                        </form>-->
<!--                    </div>-->
                </div>
                <div class="mt-12 border-t border-gray-200 pt-8 md:flex md:items-center md:justify-between lg:mt-16">
<!--                    <div class="flex space-x-6 md:order-2">-->
<!--                        <a v-for="item in footerNavigation.social" :key="item.name" :href="item.href" class="text-gray-400 hover:text-gray-500">-->
<!--                            <span class="sr-only">{{ item.name }}</span>-->
<!--                            <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />-->
<!--                        </a>-->
<!--                    </div>-->
                    <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1">
                        &copy; 2023 KPS Pools, LLC. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import { defineComponent, h } from 'vue'
import { Popover, PopoverButton, PopoverGroup, PopoverPanel } from '@headlessui/vue'
import {
    AnnotationIcon,
    ChatAlt2Icon,
    ChatAltIcon,
    DocumentReportIcon,
    HeartIcon,
    InboxIcon,
    MenuIcon,
    PencilAltIcon,
    QuestionMarkCircleIcon,
    ReplyIcon,
    SparklesIcon,
    TrashIcon,
    UsersIcon,
    XIcon,
} from '@heroicons/vue/outline'
import { ChevronDownIcon } from '@heroicons/vue/solid'

const solutions = [
    {
        name: 'Inbox',
        description: 'Get a better understanding of where your traffic is coming from.',
        href: '#',
        icon: InboxIcon,
    },
    {
        name: 'Messaging',
        description: 'Speak directly to your customers in a more meaningful way.',
        href: '#',
        icon: AnnotationIcon,
    },
    { name: 'Live Chat', description: "Your customers' data will be safe and secure.", href: '#', icon: ChatAlt2Icon },
    {
        name: 'Knowledge Base',
        description: "Connect with third-party tools that you're already using.",
        href: '#',
        icon: QuestionMarkCircleIcon,
    },
]
const features = [
    {
        name: 'Unlimited Inboxes',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: InboxIcon,
    },
    {
        name: 'Manage Team Members',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: UsersIcon,
    },
    {
        name: 'Spam Report',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: TrashIcon,
    },
    {
        name: 'Compose in Markdown',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: PencilAltIcon,
    },
    {
        name: 'Team Reporting',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: DocumentReportIcon,
    },
    {
        name: 'Saved Replies',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: ReplyIcon,
    },
    {
        name: 'Email Commenting',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: ChatAltIcon,
    },
    {
        name: 'Connect with Customers',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: HeartIcon,
    },
]
const metrics = [
    { id: 1, stat: '8K+', emphasis: 'Companies', rest: 'use laoreet amet lacus nibh integer quis.' },
    { id: 2, stat: '25K+', emphasis: 'Countries around the globe', rest: 'lacus nibh integer quis.' },
    { id: 3, stat: '98%', emphasis: 'Customer satisfaction', rest: 'laoreet amet lacus nibh integer quis.' },
    { id: 4, stat: '12M+', emphasis: 'Issues resolved', rest: 'lacus nibh integer quis.' },
]
const footerNavigation = {
    solutions: [
        { name: 'Marketing', href: '#' },
        { name: 'Analytics', href: '#' },
        { name: 'Commerce', href: '#' },
        { name: 'Insights', href: '#' },
    ],
    support: [
        { name: 'Pricing', href: '#' },
        { name: 'Documentation', href: '#' },
        { name: 'Guides', href: '#' },
        { name: 'API Status', href: '#' },
    ],
    company: [
        { name: 'About', href: '#' },
        { name: 'Blog', href: '#' },
        { name: 'Jobs', href: '#' },
        { name: 'Press', href: '#' },
        { name: 'Partners', href: '#' },
    ],
    legal: [
        { name: 'Claim', href: '#' },
        { name: 'Privacy', href: '#' },
        { name: 'Terms', href: '#' },
    ],
    social: [
        {
            name: 'Facebook',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', {
                            'fill-rule': 'evenodd',
                            d:
                                'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z',
                            'clip-rule': 'evenodd',
                        }),
                    ]),
            }),
        },
        {
            name: 'Instagram',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', {
                            'fill-rule': 'evenodd',
                            d:
                                'M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z',
                            'clip-rule': 'evenodd',
                        }),
                    ]),
            }),
        },
        {
            name: 'Twitter',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', {
                            d:
                                'M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84',
                        }),
                    ]),
            }),
        },
        {
            name: 'GitHub',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', {
                            'fill-rule': 'evenodd',
                            d:
                                'M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z',
                            'clip-rule': 'evenodd',
                        }),
                    ]),
            }),
        },
        {
            name: 'Dribbble',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', {
                            'fill-rule': 'evenodd',
                            d:
                                'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z',
                            'clip-rule': 'evenodd',
                        }),
                    ]),
            }),
        },
    ],
}

export default {
    components: {
        Popover,
        PopoverButton,
        PopoverGroup,
        PopoverPanel,
        ChevronDownIcon,
        InboxIcon,
        MenuIcon,
        SparklesIcon,
        XIcon,
    },
    setup() {
        return {
            solutions,
            features,
            metrics,
            footerNavigation,
        }
    },
}
</script>
