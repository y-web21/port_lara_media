#!/usr/bin/env bash
# shellcheck disable=SC2034,SC2164
SCRIPT_DIR=$(cd "$(dirname "$0")"; pwd)
# ===============================================
# project private settings
. "${SCRIPT_DIR}/.env.aws"

# project settings
STACK_NAME=port-lara-media-Stack
GITHUB_REPO='y-web21/port_lara_media'
BRANCH='feature/#20'
DEPLOY_APP_NAME='deploy-lara_port_media'
DEPLOY_GROUP_NAME='DeployGroup-'$DEPLOY_APP_NAME
# ===============================================

CFN_YAML="$(dirname ${SCRIPT_DIR})/cloudformation.yml"

# S3バケットが消せずにスタックが削除できない場合に使用します。指定したバケット自体をデータごと消す。
s3-remove-bucket() {
  if [[ $# -ne 1 ]];then
    echo 'require 1 arg.'
    echo 'arg1 = S3 URI  e.g s3://xxxx'
    echo '指定可能なバケットは以下のとおりです'
    aws s3 ls
    exit 1
  fi
  read -p '強制的にS3バケットを削除しますか？ (y/N): ' yn
  case "$yn" in
  [yY]*)
    echo "aws s3 rb s3://${1} --force"
    aws s3 rb s3://${1} --force
    ;;
  *)
    :
    ;;
  esac
}

# 初回用。プロジェクトにかかわるAWSリソース作成の作成に使います
cfn-create-prj-stack() {
  aws cloudformation create-stack \
  --template-body file://${CFN_YAML} \
  --capabilities CAPABILITY_NAMED_IAM \
  --stack-name ${STACK_NAME} \
  --parameters \
    ParameterKey='CodeStarGithubConnectionArn',ParameterValue="${CODE_STAR_CONNECTION_ARN}" \
    ParameterKey='PipelineGitHubRepo',ParameterValue="${GITHUB_REPO}" \
    ParameterKey='PipelineGitHubBranch',ParameterValue="${BRANCH}"
}

# AWSからプロジェクトリソースを完全削除するために使います
cfn-delete-prj-stack() {
  echo 'プロジェクトにかかわるすべてのAWSリソースが削除され取り返しがつきません'
  read -p '実行しますか？ (y/N): ' yn
  case "$yn" in
  [yY]*)
    echo "以下のコマンドを入力してください"
    echo "aws cloudformation delete-stack --stack-name ${STACK_NAME}"
    S3URI=$(cat <(aws deploy get-deployment-group --application-name $DEPLOY_APP_NAME --deployment-group-name $DEPLOY_GROUP_NAME --query deploymentGroupInfo.targetRevision.s3Location.bucket --output text))
    echo "aws s3 rb ${S3URI} --force"
    ;;
  *)
    :
    ;;
  esac
}

ls(){
  declare -F | awk '{print $3}'
}

for f in $(declare -F | awk '{print $3}');do
  if [[ $1 = "$f" ]];then
    "${f}" "${@:2}"
    exit 0
  fi
done
echo "no such function: ${1}"
