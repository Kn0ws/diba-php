# Intent Documentation

## Welcome
**Description**: DIBA PHPの起動確認用Intent。JSONでメッセージを返す。

- **Tags**: core, example, json
- **Method**: `GET`
- **Path**: `/welcome`
- **Executors**: `WelcomeExecutor@sayWelcome`
- **Response**: `Diba\Responses\Json`

**Example:**

```yaml
input:
  name: Taro
expected:
  message: 'Welcome to DIBA PHP!'
  time: '2025-04-19 23:13:30'
```


## WelcomeHtml
**Description**: DIBA PHPの起動確認用Intent。HTMLでメッセージを返す。

- **Tags**: core, example, html
- **Method**: `GET`
- **Path**: `/welcome/html`
- **Executors**: `WelcomeExecutor@sayWelcomeHtml`
- **Response**: `Diba\Responses\Html`


## WelcomeRedirect
**Description**: DIBA PHPの起動確認用Intent。リダイレクトを返す。

- **Tags**: core, example, redirect
- **Method**: `GET`
- **Path**: `/welcome/redirect`
- **Executors**: `WelcomeExecutor@redirect`
- **Response**: `Diba\Responses\Redirect`


## WelcomeRequire
**Description**: name パラメータが必須の Welcome Intent。

- **Tags**: core, example, require
- **Method**: `GET`
- **Path**: `/welcome/require`
- **Executors**: `WelcomeExecutor@sayWelcomeRequire`
- **Response**: `Diba\Responses\Json`


## WelcomeView
**Description**: DIBA PHPの起動確認用Intent。Viewでメッセージを返す。

- **Tags**: core, example, view
- **Method**: `GET`
- **Path**: `/welcome/view`
- **Executors**: `WelcomeExecutor@sayWelcomeView`
- **Response**: `Diba\Responses\ViewResponse`


## WelcomeSmart
**Description**: Acceptに応じたレスポンスを返す。

- **Tags**: core, example, accept
- **Method**: `GET`
- **Path**: `/welcome/smart`
- **Executors**: `WelcomeExecutor@sayWelcomeSmart`
- **Response**: ``


## WelcomeWithEvent
**Description**: DIBA PHPの起動確認用Intent。JSONでメッセージを返す。

- **Tags**: core, example, event
- **Method**: `GET`
- **Path**: `/welcome/event`
- **Executors**: `WelcomeExecutor@sayWelcomeEvent`
- **Response**: `Diba\Responses\Json`


## WelcomeEventEffect
**Description**: DIBA PHPの起動確認用Intent。JSONでメッセージを返す。

- **Tags**: core, example, event
- **Method**: `GET`
- **Path**: `-`
- **Executors**: `WelcomeExecutor@sayWelcomeEventEmit`
- **Response**: `Diba\Responses\Json`


## WelcomeWithId
**Description**: DIBA PHPのパスパラメータIntent。JSONでメッセージを返す。

- **Tags**: 
- **Method**: `GET`
- **Path**: `/welcomeWithId/{id}`
- **Executors**: `WelcomeExecutor@sayWelcomeWithId`
- **Response**: `Diba\Responses\Json`


## WelcomeWithIntId
**Description**: DIBA PHPのパスパラメータIntent。INT制限をかけ、JSONでメッセージを返す。

- **Tags**: 
- **Method**: `GET`
- **Path**: `/welcomeWithIntId/{id}`
- **Executors**: `WelcomeExecutor@sayWelcomeWithId`
- **Response**: `Diba\Responses\Json`


## WelcomeFilter
**Description**: -

- **Tags**: 
- **Method**: `GET`
- **Path**: `/welcomeFilter`
- **Executors**: `WelcomeExecutor@sayWelcomeWithFilter`
- **Response**: `Diba\Responses\Json`
- **Middlewares:**
  - Before: RequireAuth, CheckPermission
  - After:  LogAccess


## SampleIntent
**Description**: Plugins/Sample Intent Description

- **Tags**: 
- **Method**: `GET`
- **Path**: `/plugins/sample`
- **Executors**: `Plugins\Sample\Executors\SampleExecutor@handle`
- **Response**: `Responses\Json`
- **Plugin**: Sample


