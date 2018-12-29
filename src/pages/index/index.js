var app = getApp();
var util = require('../../utils/util.js');
Page({
    getOpenTypeUserInfo : function(u) {
        debugger
        console.log(u);
        app.globalData.userInfo = u.detail.userInfo;
        this.login();
    },

    onReady: function () {
        //已经登陆过，跳转首页
        if(app.globalData.userInfo && app.globalData.userInfo.islogin) {
            wx.navigateTo({
                url: '/pages/billboard/index',
            })
        }
    },

    login:function(success){
        var that=this;
        this.getUserInfo(function (obj) {
          that.getMemberInfo(obj, function (result) {
            app.globalData.userInfo = result;
            app.globalData.token = result.token;
            app.globalData.sessionid = result.sessionid;
            if (success) success();
          });
        });
    },

    getMemberInfo:function(obj,cb){
        wx.request({
          url: app.ServerUrl()+'/api/login.php',
          method:'POST',
          data:obj,
          success:function(res){
            console.log(res);
            if(parseInt(res.data.err)==0){
              if(cb)cb(res.data.result);
            }else{
              wx.showModal({
                title: '',
                content: res.data.msg
              })
            }
          }
        })
      },

      getUserInfo:function(cb){
        if(app.globalData.userInfo.islogin){
          typeof cb == "function" && cb(app.globalData.userInfo)
        }else{
          //调用登录接口
          wx.login({
            success: function (res) {
                app.globalData.userInfo.islogin = true;
                wx.request({
                  url: app.ServerUrl()+'/api/getopenid.php',
                  method:'POST',
                  data:{
                    code:res.code
                  },
                  success: function (res3) {
                    console.log(res3);
                    app.globalData.session_key = res3.data.result.session_key;
                    app.globalData.userInfo.openid = res3.data.result.openid;
                    typeof cb == "function" && cb(app.globalData.userInfo)
                  }
                })
            }
          })
        }
      },
})