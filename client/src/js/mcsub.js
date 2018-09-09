/**
 * Mcsub (MailChimp Subsciptions)
 * ES6 Simple Mcsub Client Side Subscription wrapper for
 * Email subscriptions
 *
 */

/* eslint-disable */

export default class Mcsub {

	constructor(element, options) {
		this.config = Mcsub.mergeSettings(options);
        this.element = document.querySelector(element);
		this.config.user === '' && Mcsub.require('user id'); // Check
		this.config.list === '' && Mcsub.require('list id'); // Check
		this.form = this.element.children[0];
		this.button = this.form.querySelector('button[type="submit"]');
		this.inputs = this.form.querySelectorAll('input');
		this.response = this.element.querySelector(this.config.response);
		this.robot(`b_${this.config.user}_${this.config.list}`);
		this.init();
	}

	static require(option) {
		throw new Error(`You are missing the ${option} 😭`);
	}

	/**
	 * Merge Defaults
	 * @param {merge} options
	 */
	static mergeSettings(options) {

		const settings = {
			user: '', // Required
			list: '', // Required
			callback: 'cb',
			response: '#response',
			onInit: () => {},
			onSubmit: () => {},
			complete: () => {},
			onSuccess: () => {},
			onError: () => {}
		};

		const userSettings = options;

		for (const attrname in userSettings) {
			settings[attrname] = userSettings[attrname];
		}
		return settings;
	}

	/**
	 *
	 * @param {element} element
	 * @param {elements attributes} attrs
	 */
	static setAttrs(element, attrs) {
		for (let key in attrs) {
			element.setAttribute(key, attrs[key]);
		}
	}

	/**
	 * Dynamic Default Robot input
	 * Required by Mcsub
	 * @param {input name} name
	 */
	robot(name) {
		const robot = document.createElement('input');
		Mcsub.setAttrs(robot, {
			'id': 'robot',
			'type': 'text',
			'tabindex': '-1',
			'value': "",
			'name': name,
			'style': 'position: absolute; left: -5000px;',
			'aria-hidden': 'true'
		});
		this.form.appendChild(robot);
	}

	/**
	 * Initialize fn.
	 */
	init() {

		// Change URL
		this.url = this.form.action.replace('/post', '/post-json?');
        // Add Submit Listener
		this.form.addEventListener('submit', event => this.submit(event));

		// onInit
		this.config.onInit.call(this);

		// Apply fallback default (after submit listener)
		this.default();
	}

	/**
	 * Apply Default Mcsub attributes
	 * Will act as a fallback.
	 */
	default () {
		Mcsub.setAttrs(this.form, {
			'action': this.form.action.replace('/post', `/post?u=${this.config.user}&id=${this.config.list}`),
			'method': 'post',
			'target': '_blank'
		});
		for (let i = 0; i < this.inputs.length; i++) {
			this.inputs[i].id = `mce-${this.inputs[i].name}`;
		}
	}

	/**
	 * Submit Event fn.
	 * @param {Form} event
	 */
	submit(event) {

		// Prevents Submission
		event.preventDefault();

		// Checks for Spam
		if (this.form.lastChild.value !== '') {
			return false;
		}

		// Call onSubmit fn
		this.config.onSubmit.call(this);

		// Empty data value
		let data = '';

		// Build script src URL
		for (let i = 0; i < this.inputs.length; i++) {
			data += `u=${this.config.user}&id=${this.config.list}&c=${this.config.callback}&${this.inputs[i].name}=${encodeURIComponent(this.inputs[i].value)}`;
		}

		this.setScript(data);
	}

	/**
	 * Set Appended script element attributes
	 * @param {script src} data
	 */
	setScript(data) {
		const script = document.createElement('script');
		script.setAttribute('data-id', this.element.id);
		document.body.appendChild(script);
		this.getScript(`[data-id="${this.element.id}"]`, data);
	}

	/**
	 * Get Appended Script & Run singularity Check.
	 * @param {script id} id
	 * @param {script src} data
	 */
	getScript(id, data) {

		// Select Script element/s
		const script = document.querySelectorAll(id);

		// Check if Script Exists
		if (!script) {
			return false;
		}

		// if more than 1 script element, remove it.
		// else set the the src
		// then Pass to Success fn.
		for (let i = 0; i < script.length; i++) {
			Promise.resolve().then(() => {
				if (i > 1) {
					script[i].parentElement.removeChild(script[i]);
				} else {
					script[i].src = this.url + data;
				}
			}).then(() => this.success(script[i]));
		}
	}

	respond(el, data) {
		return {
			'error': () => {
                console.log(el);
				// Apply Error Class
				el.classList.contains('success') ?
					el.classList.replace('success', 'error') :
					el.classList.add('error');

				// Apply Response Message from MailChimp
				el.innerHTML = data.msg.includes(' - ') ?
					data.msg.substring(3) :
					data.msg;

				// Display Response element (Removes display:none style)
				el.hasAttribute('style') && el.removeAttribute('style');

				// onError
				this.config.onError.call(this);
			},
			'success': () => {

				// Apply Success Class
				el.classList.contains('error') ?
					el.classList.replace('error', 'success') :
					el.classList.add('success');

				// Apply Response Message from MailChimp
				el.innerHTML = data.msg;

				// Display Response element (Removes display:none style)
				el.hasAttribute('style') && el.removeAttribute('style');

				// onSuccess Callback Function
				this.config.onSuccess.call(this);
			}
		}[data.result]();
	}

	/**
	 * Successful Post of form, clean up script.
	 * @param {script element} script
	 */
	success(script) {

		// Callback
		window[this.config.callback] = data => {
			this.response = data;
			this.config.complete.call(this);
		};
	}
};
