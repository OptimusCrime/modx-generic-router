# MODX Generic Router

Note: This repository is under prototyping

## Process

### Lexer

Take the raw input and tokenize each character. The only types of tokens we care about are:

* `BracketsToken`: either `[` or `]`
* `DashToken`: `-`
* `DotToken`: `.`
* `EqualSignToken`: `=`
* `NewlineToken`: `\n`
* `PipeToken`: `|`
* `PlusSignToken`: `+`
* `RegularToken`: Any content that does not fit into any other types of tokens
* `ThildeToken`: `~`
* `WhiteSpaceToken`: Whitespaces

### Parser

The parser does several tasks and converts the tokens from the lexer into an abstract syntax tree that we can store in the database to avoid having to parse the expressions each time. This AST can be exported and imported as a JSON string. The process involves the following steps:

#### 1. Concatenate

We take the stream of tokens from the lexer and we attempt to concatenate two consecutive `[` or `]`. We use these groups, dubbed `TagEndToken` and `TagStartToken` to define places where out parser differentiate between regular text and router expressions.

#### 2. Structure

The start and end tags from the previous steps are used to build a tree like structure. This structure uses `Node`s to build the parent-children-relationship.

#### 3. Relax

Any content that is at the root level of the tree is converted into regular text again. We do not care about this content and we do not need to parse it any further.

#### 4. Context Parsing

This is the tricky part. We now iterate each `Node` in the tree (that are not just text from the relaxation), and we attempt to make sense out it. This process follows these rules:

1. If there are no children in the `Node`, we have an empty context, this results in an error for the `Route`.
2. The first child of the `Node` can only be the following types of tokens: A plus sign, an integer, or a thilde token.
