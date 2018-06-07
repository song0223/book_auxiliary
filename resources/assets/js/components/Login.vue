<template>
    <div class="form-group">
        <div class="form-group" :class="{'has-error': errorPhone}">
            <label for="phone" class="col-md-4 control-label">手机号码</label>

            <div class="col-md-4">
                <input type="text" class="form-control" name="phone" v-model="phone" required>

                <span class="help-block" v-show="errorPhone">
                    <strong>{{errorPhone}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group" :class="{'has-error': errorPwd}">
            <label for="password" class="col-md-4 control-label">密码</label>

            <div class="col-md-4">
                <input id="password" type="password" class="form-control" name="password" required>

                <span class="help-block" v-show="errorPwd">
                    <strong>{{errorPwd}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" v-model="form.is_remember"> Remember Me
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>
                <a class="btn btn-link" href="#">
                    Forgot Your Password?
                </a>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        name: 'loginForm',
        props: [],
        data () {
            return {
                form: {
                    phone: null,
                    code: null,
                    counter: 0,
                    is_remember: false
                },
            }
        },
        computed: {
            errorPhone () {
                if (this.phone == null || this.checkPhone(this.phone)) {
                    return false
                }

                return '手机号码不正确！';
            },
            errorPwd () {
                if (this.code == null || this.code.length == 4) {
                    return false
                }

                return '4 位验证码'
            }
        },
        methods: {
            checkPhone: function (phone) {
                return !!phone.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[0678]|18[0-9]|14[57])[0-9]{8}$/)
            }
        }
    }
</script>