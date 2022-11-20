# cloudformation

## 動作確認手順

前提

- パブリックIP(54.250.12.153)は変動です。[EC2 Management Console](https://ap-northeast-1.console.aws.amazon.com/ec2/home?region=ap-northeast-1)から都度確認してください。
- コンソールからも確認可能ですが、手順は AWS CLI 前提です。[Installing or updating the latest version of the AWS CLI - AWS Command Line Interface](https://docs.aws.amazon.com/cli/latest/userguide/getting-started-install.html)
- 事前にキーペアの作成が必要です。(KeyName: lara_port_media-Key)($PEM)

1. public ip の取得

    ```bash
    # project root で実行
    STACK_NAME=test-cfn-yml
    aws cloudformation create-stack --template-body file://cloudformation.yml --capabilities CAPABILITY_NAMED_IAM --stack-name $STACK_NAME

    # 問い合わせが CREATE_COMPLETE になるまで待機
    aws cloudformation list-stacks | jq -r --arg name $STACK_NAME '.StackSummaries[] | select(.StackName == $name) | [.StackStatus] | @tsv'

    # Ip を取得
    $EC2_IP=$(aws ec2 describe-instances --filters Name=tag:aws:cloudformation:stack-name,Values=$STACK_NAME --query "Reservations[].Instances[].PublicIpAddress" | jq -r .[])
    ```

1. test 用に nginx を動作させる

     ```bash
     ssh -i $PEM ec2-user@${EC2_IP}
     ```

    `Amazon Linux`での操作

     ```bash
     docker pull nginx
     docker run -d -p 80:80 nginx
     ```

1. ブラウザから動作確認

  `http://54.250.12.153/`で「Welcome to nginx!」を確認。

1. テストスタックの削除

    ```bash
    aws cloudformation delete-stack --stack-name $STACK_NAME
    unset EC2_IP STACK_NAME
    ```
