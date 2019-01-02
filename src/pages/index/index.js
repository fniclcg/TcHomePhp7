var app = getApp();
var util = require('../../utils/util.js');
Page({
    getOpenTypeUserInfo : function(u) {
        //console.log(u);
        app.globalData.userInfo = u.detail.userInfo;
        this.login(function() {
            wx.switchTab({
                url: '/pages/billboard/index'
            })
        });
    },

    onReady: function () {
      var that = this;
      // 查看是否授权
      wx.getSetting({
        success(res) {
          if (res.authSetting['scope.userInfo']) {
            // 已经授权，可以直接调用 getUserInfo 获取头像昵称
            wx.getUserInfo({
              success(res) {
                app.globalData.userInfo = res.userInfo;
                that.login(function() {
                  wx.switchTab({
                      url: '/pages/billboard/index'
                  })
                });
              }
            })
          }
        }
      })
      
      //已经登陆过，跳转首页
      // if(app.globalData.userInfo && app.globalData.userInfo.islogin) {
      //     wx.switchTab({
      //         url: '/pages/billboard/index'
      //     })
      // }
    },

    login:function(success){
        var that=this;
        this.getUserInfo(function (obj) {
          that.getMemberInfo(obj, function (result) {
            console.log("[xxxxxxxxxxx] => ", result);
            app.globalData.userInfo = result;
            app.globalData.userInfo.islogin = true;
            app.globalData.token = result.token;
            app.globalData.sessionid = result.sessionid;
            console.log("[xxxxxx-global] => ", app.globalData);
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
                    //console.log(res3);
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