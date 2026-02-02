const fs = require('fs');
const readline = require('readline');
const path = require('path');
const { exec } = require('child_process');

// Helper function to prompt user for input
const askQuestion = (query) => {
  const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
  });

  return new Promise((resolve) => rl.question(query, (ans) => {
    rl.close();
    resolve(ans);
  }));
};

// Function to generate a random prefix
const generatePrefix = () => {
  return 'wp_' + Math.random().toString(36).substr(2, 5) + '_';
};

// Function to replace or add lines in .env content
const updateEnvContent = (content, key, value, useDoubleQuotes = false) => {
  const regex = new RegExp(`^${key}=.*$`, 'm');
  const formattedValue = useDoubleQuotes ? `"${value}"` : `'${value}'`;
  
  if (content.match(regex)) {
    return content.replace(regex, `${key}=${formattedValue}`);
  } else {
    return content + `\n${key}=${formattedValue}`;
  }
};

// Function to extract domain from the working directory
const extractDomain = async (workingDir) => {
  const match = workingDir.match(/\/sites\/([^/]+)\/files/);
  if (match && match[1]) {
    return match[1];
  } else {
    // If automatic extraction fails, prompt for manual entry
    console.log("Unable to extract domain from working directory (local environment detected).");
    const domain = await askQuestion('Enter the domain name (e.g., example.test): ');
    return domain;
  }
};

// Function to ensure DB_PREFIX ends with an underscore
const ensureTrailingUnderscore = (prefix) => {
  return prefix.endsWith('_') ? prefix : prefix + '_';
};

// Function to generate .env file
const generateEnvFile = async () => {
  const envFilePath = path.resolve('.env');
  const workingDir = process.cwd();

  let content = '';

  const dbName = await askQuestion('Enter the database name (DB_NAME): ');
  const dbUser = await askQuestion('Enter the database user (DB_USER): ');
  const dbPassword = await askQuestion('Enter the database password (DB_PASSWORD): ');
  const dbHost = await askQuestion('Enter the database host [default: 127.0.0.1]: ') || '127.0.0.1';
  let dbPrefix = await askQuestion('Enter the database prefix (DB_PREFIX) or press enter to auto-generate: ');

  dbPrefix = ensureTrailingUnderscore(dbPrefix || generatePrefix());

  const wpEnv = await askQuestion('Select the environment (WP_ENV: 1) development, 2) staging, 3) production): ');

  const wpEnvValue = {
    '1': 'development',
    '2': 'staging',
    '3': 'production'
  }[wpEnv] || 'development';

  const domain = await extractDomain(workingDir);
  const wpHome = `https://${domain}`;
  
  // Ask for temp directory (with sensible default for local environments)
  const defaultTempDir = workingDir.match(/\/sites\//) ? `/sites/${domain}/tmp/` : `${workingDir}/tmp/`;
  const wpTempDir = await askQuestion(`Enter the temp directory path [default: ${defaultTempDir}]: `) || defaultTempDir;

  content = updateEnvContent(content, 'DB_NAME', dbName);
  content = updateEnvContent(content, 'DB_USER', dbUser);
  content = updateEnvContent(content, 'DB_PASSWORD', dbPassword);
  content = updateEnvContent(content, 'DB_HOST', dbHost);
  content = updateEnvContent(content, 'DB_PREFIX', dbPrefix);
  content = updateEnvContent(content, 'WP_ENV', wpEnvValue);
  content = updateEnvContent(content, 'WP_HOME', wpHome);
  content = updateEnvContent(content, 'WP_SITEURL', '${WP_HOME}/wp', true);  // Use double quotes
  content = updateEnvContent(content, 'WP_DEBUG_LOG', '~/logs/debug.log');
  content = updateEnvContent(content, 'WP_TEMP_DIR', wpTempDir);
  content = updateEnvContent(content, 'CREARE_GLOBAL_KEYS', '/etc/creare/keys.json');

  // Save the .env file without the salts
  fs.writeFileSync(envFilePath, content, 'utf8');

  // Generate salts using the generate-salts.js script and append them to the .env file
  exec('node generate-salts.js env', (error, stdout, stderr) => {
    if (error) {
      console.error(`Error generating salts: ${error.message}`);
      return;
    }
    if (stderr) {
      console.error(`Error: ${stderr}`);
      return;
    }

    // Append the generated salts to the .env file
    fs.appendFileSync(envFilePath, '\n' + stdout.trim());
    console.log(".env file has been updated successfully with salts.");
  });
};

generateEnvFile();