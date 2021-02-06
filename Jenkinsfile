def git_url = 'https://github.com/initedit/api.note.initedit.git'
def git_branch = 'master'

pipeline
{
    options{
        timestamps()
        timeout(time: 10, unit: 'MINUTES')
        buildDiscarder(logRotator(numToKeepStr: '10'))
    }
    agent{
        label 'lp-knode-02'
    }
    stages
    {
        /*stage('Git-checkout')
        {
            steps
            {
                git credentialsId: 'github', url: git_url , branch: git_branch
            }
        }*/
        
        stage('Sonarqube-anaylysis')
        {
            steps
            {
                    sh '''
                    echo "sonarqube analysis"
                    '''
            }
        }
        
        stage('Build')
        {
            
            steps
            {
                sh '''
                composer install
                /usr/bin/mv .env.example .env
                /usr/bin/cp /opt/.env .
                
                '''
            }
        }
        
        stage('Deploy')
        {
            steps
            {
                sh """
                rsync -parv -e 'ssh -o StrictHostKeyChecking=no' . $note_user@$web1:$api_test_note_public_html_path
                """
            }
        }
        
        stage('Smoke-test')
        {
            steps
            {
                sh '''
                ab -n 10 -c 2 -s 60 https://test.note.initedit.com/
                '''
            }
        }
    }
}
