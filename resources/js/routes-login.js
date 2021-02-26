import Login from './views/pages/login.vue';

export default [
    {
		path: '/',
		redirect: '/login',
	},
	{
		path: '/login',
		component: Login,
    }
];
