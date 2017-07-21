validator
=========
The javascript validation code is based on jQuery. The Validator is cross-browser and will give you the power to use future-proof input types such as ‘tel’, ‘email’, ‘number’, ‘date’, and ‘url’. I can sum this as a ‘template’ for creating web forms.
JavaScriptの検証コードはjQueryに基づいています。 Validatorはクロスブラウザーで、 'tel'、 'email'、 'number'、 'date'、 'url'などの将来的に使用できる入力タイプを使用する権限を与えます。 私はこれをWebフォームを作成するための「テンプレート」としてまとめることができます。

In the semantic point-of-view, I believe that this method is very clean and…appropriate. This is how forms should be, IMHO.
意味論的な観点からは、私はこの方法がきれいで適切であると信じています。 これはフォームがIMHOであるべき方法です。

[DEMO PAGE](http://yaireo.github.io/validator)

### Why should you use this? なぜこれを使うべきですか？

* Cross browser validation クロスブラウザ検証
* Deals with all sorts of edge cases あらゆる種類のケースを扱う
* Utilize new HTML5 types for unsupported browsers サポートされていないブラウザに新しいHTML5タイプを利用する
* Flexible error messaging system フレキシブルなエラーメッセージングシステム
* Light-weight (10kb + comments) 軽量（10kb +コメント）

## Validation types support 検証タイプのサポート
HTML5 offers a wide selection of input types. I saw no need to support them all, for example, a checkbox should not be validated as ‘required’ because why wouldn’t it be checked in the first place when the form is rendered?
HTML5は幅広い種類の入力を提供します。 フォームをレンダリングするときに最初にチェックされないのはなぜですか？なぜなら、チェックボックスを「必須」として検証するべきではないからです。

For a full list of all the available Types, visit the working draft page.
使用可能なすべてのタイプの完全なリストについては、作業中のドラフトページを参照してください。
These input types can be validated by the the JS for – `<input type='foo' name='bar' />`. (Support is synthesized)
これらの入力タイプは、JSが '<input type =' foo 'name =' bar '/> `のために検証することができます。 （サポートが合成されます）

* Text
* Email
* Password
* Number
* Date
* URL
* Search
* File
* Tel
* Checkbox
* Hidden – Hidden fields can also have the ‘required’ attribute 非表示のフィールドには '必須'属性

The below form elements are also supported:
以下のフォーム要素もサポートされています：

* Select – Useing a ‘required’ class because there is no such attribute for ‘select’ element 'select'要素にそのような属性がないため、 'required'クラスを使用しています
* Textarea


## Basic semantics
    <form action="" method="post" novalidate>
    	<fieldset>
    		<div class="item">
    			<label>
    				<span>Name</span>
    				<input data-validate-lengthRange="6" data-validate-words="2" name="name" placeholder="ex. John f. Kennedy" required="required" type="text" />
    			</label>
    			<div class='tooltip help'>
    				<span>?</span>
    				<div class='content'>
    					<b></b>
    					<p>Name must be at least 2 words</p>
    				</div>
    			</div>
    		</div>
    		<div class="item">
    			<label>
    				<span>email</span>
    				<input name="email" required="required" type="email" />
    			</label>
    		</div>
         		...


### Explaining the DOM DOMの説明
First, obviously, there is a Form element with the novalidate attribute to make sure to disable the native HTML5 validations (which currently suck). proceeding it there is a Fieldset element which is not a must, but acts as a “binding” box for a group of fields that are under the same “category”. For bigger forms there are many times field groups that are visually separated from each other for example. Now, we treat every form field element the user interacts with, whatsoever, as an “item”, and therefor these “items” will be wraped with `<div class='item'>`. This isolation gives great powers.
まず、明らかに、novalidate属性を持つForm要素が存在し、ネイティブHTML5検証（現在はサック）を無効にすることを確認します。 それに進むには、必須ではないFieldset要素がありますが、同じ「カテゴリ」の下にあるフィールドのグループの「バインディング」ボックスとして機能します。 より大きなフォームの場合、例えば視覚的に互いに離れているフィールドグループが何度も存在する。 ここでは、ユーザーが "アイテム"として扱うすべてのフォームフィールド要素を扱います。したがって、これらの "アイテム"は `<div class = 'item'>`で囲まれます。 この隔離は大きな力を与えます。
Next, inside an item, there will typically be an input or select or something of the sort, so they are put inside a `<label>` element, to get rid of the (annoying) for attribute, on the label (which also require us to give an ID to the form field element), and now when a user clicks on the label, the field will get focused. great. Going back to the label’s text itself, we wrap it with a `<span>` to have control over it’s style.
次に、項目の中には、通常、ソートの入力や選択などがありますので、ラベルの属性（迷惑なもの）を取り除くために、それらを `<label>`要素の中に入れます フォームフィールド要素にIDを指定する必要があります）、ユーザーがラベルをクリックすると、フィールドにフォーカスが当てられます。 すばらしいです。 ラベルのテキスト自体に戻って、スタイルを制御するために、ラベルのテキストを `<span>`で囲みます。

The whole approach here is to define each form field (input, select, whatever) as much as possible with HTML5 attributes and also with custom attributes.
HTML5の属性とカスタム属性を使用して、できるだけ各フォームフィールド（入力、選択など）を定義するというアプローチ全体です。

| Attribute                  | Purpose                                                                                                                                                                                                                                                                                                                         |
|----------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| required                   | Defines that this field should be validated (with JS by my implementation and not via native HTML5 browser defaults)  このフィールドを検証する必要があることを定義します（実装ではJS、HTML5ブラウザのデフォルトのデフォルトでは使用しません）                                                                                                             |
| placeholder                | Writes some placeholder text which usually describes the fields with some example input (not supported in IE8 and below) いくつかのプレースホルダテキストを記述します。プレースホルダテキストは、通常、フィールドをいくつかの入力例で記述します（IE8以下ではサポートされていません）                                                            |
| pattern                    | Defines a pattern which the field is evaluated with. Available values are:<br>**numeric** - Allow only numbers<br>**alphanumeric** - Allow only numbers or letters. No special language characters<br>**phone** - Allow only numbers, spaces or dashes.<br><br>Alternatively, you may write your own custom regex here as well. フィールドが評価されるパターンを定義します。 使用可能な値は次のとおりです。<br> **数値** - 数値のみを許可する。** **英数字** - 数字または文字のみを許可する。 特別な言語の文字はありません。** phone ** - 数値、スペース、またはダッシュのみを許可します。また、カスタム正規表現もここに書くことができます。 |
| data-validate-words        | Defines the minimum amount of words for this field このフィールドの最小単語数を定義します。                                                                                                                                                                                                                                                                             |
| data-validate-length       | Defines the length allowed for the field (after trim). Example value: `7,11` (field can only have 7 or 11 characters). you can add how many allowed lengths you wish フィールドに許容される長さを定義します（トリム後）。 値の例： '7,11'（フィールドには7文字または11文字しか使用できません）。 あなたは希望の長さをいくつ追加することができます                                                                                                                                                           |
| data-validate-length-range | Defines the minimum and/or maximum number of chars in the field (after trim). value can be `4,8` for example, or just `4` to set minimum chars only フィールド内の最小および/または最大文字数を定義します（トリム後）。 値は例えば「4,8」とすることができ、最小の文字のみを設定するには「4」とすることができます                                                                                                                                                                            |
| data-validate-linked       | Defines the field which the current field’s value (the attribute is set on) should be compared to  現在のフィールドの値（属性が設定されている）を比較するフィールドを定義します。                                                                                                                                                                                                                             |
| data-validate-minmax       | For type `number` only. Defines the minimum and/or maximum value that can be in that field  タイプ `number`のみです。 そのフィールドに含めることができる最小値または最大値を定義します。                                                                                                                                                                                                                                    |




### Optional fields オプションフィールド
There is also support for optional fields, which are not validated, unless they have a value. The support for this feature is done by adding a class “optional” to a form element. Note that this should not be done along side the “required” attribute.
また、値がない限り、検証されないオプションのフィールドもサポートされています。 この機能のサポートは、フォーム要素に「オプション」クラスを追加することによって行われます。 これは、「必須」属性の横には行わないでください。


## Error messages エラーメッセージ
The validator function holds a messages object called "message", which itself holds all the error messages being shown to the user for all sort of validation errors.
バリデータ関数は、 "message"というメッセージオブジェクトを保持します。このオブジェクトは、すべての種類の検証エラーに対してユーザーに表示されるすべてのエラーメッセージを保持します。

    message = {
    	invalid			: 'invalid input',
    	empty			: 'please put something here',
    	min				: 'input is too short',
    	max				: 'input is too long',
    	number_min		: 'too low',
    	number_max		: 'too high',
    	url				: 'invalid URL',
    	number			: 'not a number',
    	email			: 'email address is invalid',
    	email_repeat	: 'emails do not match',
    	password_repeat	: 'passwords do not match',
    	repeat			: 'no match',
    	complete		: 'input is not complete',
    	select			: 'Please select an option'
    };

This object can be extended easily. The idea is to extend it with new keys which represent the name of the field you want the message to be linked to, and that custom message appear as the `general error` one. Default messages can be over-ride.
このオブジェクトは簡単に拡張できます。 そのアイデアは、メッセージをリンクさせるフィールドの名前を表す新しいキーで拡張することであり、そのカスタムメッセージは「一般的なエラー」として表示されます。 デフォルトのメッセージはオーバーライド可能です。
Example: for a given type ‘date’ field, lets set a custom (general error) message like so:
例：特定の型の 'date'フィールドの場合、次のようなカスタム（一般的なエラー）メッセージを設定できます：
    `validator.message['date'] = 'not a real date';`

Error messages can be disabled:
エラーメッセージは無効にすることができます：
    `validator.defaults.alerts = false;`

## Binding the validation to a form 検証をフォームにバインドする

There are 2 ways to validate form fields, one is on the submit event of the form itself, then all the fields are evaluated one by one. The other method is by binding the ‘checkField’ function itself to each field, for events like “Blur”, “Change” or whatever event you wish (I prefer on Blur).
フォームフィールドの検証には2つの方法があり、1つはフォーム自体の送信イベントにあり、すべてのフィールドは1つずつ評価されます。 もう1つの方法は、「ぼかし」、「変更」、または任意のイベント（ぼかしを好む）のようなイベントの場合、 'checkField'関数自体を各フィールドにバインドすることです。

###Usage example - validate on submit 使用例 - 送信時に検証する

A generic callback function using jQuery to have the form validated on the **Submit** event. You can also include your own personal validations before the **checkAll()** call.
jQueryを使用してフォームを** Submit **イベントで検証するジェネリックコールバック関数。 ** checkAll（）**コールの前に独自の個人的な検証を含めることもできます。

    $('form').submit(function(e){
    	e.preventDefault();
    	var submit = true;
    	// you can put your own custom validations below 以下の独自のカスタム検証を行うことができます

    	// check all the rerquired fields 必要なフィールドをすべて確認してください
    	if( !validator.checkAll( $(this) ) )
    		submit = false;

    	if( submit )
    		this.submit();

    	return false;
    })

###Usage example - validate on field blur event (out of focus) 使用例 - フィールドブラーイベントの検証（フォーカスのずれ）
Check every field once it looses focus (onBlur) event
フォーカス（onBlur）イベントを失ったら、すべてのフィールドをチェックしてください

    $('form').on('blur', 'input[required]', validator.checkField);

## Tooltips ツールチップ

The helper tooltips **&lt;div class='tooltip help'&gt;**, which work using pure CSS, are element which holds a small **'?'** icon and when hovered over with the mouse, reveals a text explaining what the field “item” is about or for example, what the allowed input format is.
純粋なCSSを使用して動作するヘルパーツールチップ **&lt; div class = 'tooltip help'&gt;** は小さな **'？'** アイコンを保持する要素で、マウスでマウスオーバーするとテキストが表示されます 項目 "項目"について、または例えば許可されている入力形式が何であるかを説明します。

## Classes クラス
`validator.defaults.classes` object can be modified with these classes:
`validator.defaults.classes`オブジェクトは次のクラスで変更できます：

    item    : 'item',  // class for each input wrapper 各入力ラッパーのクラス
    alert   : 'alert', // call on the alert tooltip アラートのツールチップを呼び出す
    bad     : 'bad'    // classes for bad input 悪い入力のクラス

## Bonos – multifields ボノス - 多面体

I have a cool feature I wrote which I call “multifields”. These are fields which are often use as to input a credit card or a serial number, and are actually a bunch of input fields which are “connected” to each other, and treated as one. You can see it in the demo page, and it’s included in ‘multifield.js’ file.
私が書いた "マルチスクリーン"と呼ばれるクールな機能があります。 これらは、クレジットカードや通し番号を入力するためによく使用されるフィールドであり、実際には互いに接続されて1つの入力フィールドとして扱われます。 デモページで見ることができ、 'multifield.js'ファイルに含まれています。
