const { writeFile } = require('fs');
const { argv } = require('yargs');
var dotenvExpand = require('dotenv-expand');

// read environment variables from .env file
const dotEnv = require('dotenv').config({
  path: './.env',
});
dotenvExpand.expand(dotEnv);
// read the command line arguments passed with yargs
const environment = argv.environment;
const isProduction = environment === 'prod';
const targetPath = isProduction
  ? `./src/environments/environment.prod.ts`
  : `./src/environments/environment.ts`;
// we have access to our environment variables
// in the process.env object thanks to dotenv

let API_URL = '';

const envApiURL = process.env['NG_APP_API_URL'];

if (environment === 'gitpod') {
  API_URL =
    'https://8000-' +
    (process.env['NG_APP_GITPOD_WORKSPACE_URL'] as string).split('://')[1];
} else {
  if (!envApiURL) {
    throw new Error(
      'You MUST have a NG_APP_API_URL environment variable either on the system or in .env file !'
    );
  } else {
    API_URL = envApiURL;
  }
}

const environmentFileContent = `
export const environment = {
   production: ${isProduction},
   apiUrl: "${API_URL}",
};
`;
// write the content to the respective file
writeFile(targetPath, environmentFileContent, function (err: any) {
  if (err) {
    console.log(err);
  }
  console.log(`Wrote variables to ${targetPath}`);
});
